@extends('layouts.aplication')
@section('title', 'Angsuran')
@section('content')
    <x-bar.navbar> Angsuran
        <x-slot name="content">
            <div class="container mt-4">
                <div class="row">
                    <div class="col-md-3 mb-5">
                        <div class="card shadow">
                            <div class="card-header bg-primary text-white">
                                <strong><i class="ri-qr-scan-line"></i> QR Scanner</strong>
                            </div>
                            <div class="card-body text-center">
                                <div id="reader" style="width: 100%; height: auto;"></div>
                                <h6 class="mt-3">No Anggota Terdeteksi:</h6>
                                <p id="result-text" class="fs-5 text-success fw-bold mb-0">-</p>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-9 mb-3">
                        <x-alert-message></x-alert-message>
                        <div class="card shadow">
                            <div class="card-header bg-primary d-flex justify-content-between align-items-center">
                                <h5 class="mb-0 text-white">Daftar Angsuran</h5>
                                <input type="date" id="tanggal-angsuran" class="form-control form-control-sm"
                                    style="width: auto;" value="{{ date('Y-m-d') }}">
                            </div>
                            <div class="card-body">
                                <div id="angsuran-list" class="row g-3">
                                </div>
                            </div>
                            <div class="card-footer bg-transparent border-top py-3">
                                <div class="d-flex align-items-center justify-content-between">
                                    <div class="flex-grow-1 text-start">
                                        <span class="text-muted">Total Data: <strong id="total-data">0</strong></span>
                                    </div>
                                    <div class="mx-2" style="width: 200px; text-align: center;">
                                        <span class="text-muted">Total: <strong id="total-nominal">Rp 0</strong></span>
                                    </div>
                                    <div class="ms-2">
                                        <button id="submit-button" class="btn btn-success" type="button"
                                            title="Simpan Data Angsuran">
                                            <i class="ri-save-line"></i>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <audio id="scan-sound">
                <source src="{{ asset('sounds/qr-scan-beep.mp3') }}" type="audio/mpeg">
                Browser Anda tidak mendukung audio.
            </audio>

            <meta name="csrf-token" content="{{ csrf_token() }}">
            <script src="https://cdn.jsdelivr.net/npm/html5-qrcode@2.3.8/html5-qrcode.min.js"></script>

            <script>
                const reader = document.getElementById('reader');
                const resultText = document.getElementById('result-text');
                const angsuranList = document.getElementById('angsuran-list');
                const totalDataElement = document.getElementById('total-data');
                const totalNominalElement = document.getElementById('total-nominal');
                const saveButton = document.getElementById('submit-button');

                let scanner = null;
                let angsuranData = [];

                function showScanResult(data) {
                    const scanSound = document.getElementById("scan-sound");
                    if (scanSound) {
                        scanSound.play().catch(err => console.log('Audio play failed:', err));
                    }

                    resultText.textContent = data;

                    if (scanner && typeof scanner.pause === 'function') {
                        try {
                            scanner.pause();
                        } catch (err) {
                            console.error("Gagal pause scanner:", err);
                        }
                    }

                    fetchAngsuranData(data);

                    setTimeout(() => {
                        if (scanner && typeof scanner.resume === 'function') {
                            try {
                                const resumePromise = scanner.resume();
                                if (resumePromise && typeof resumePromise.catch === 'function') {
                                    resumePromise.catch(err => {
                                        console.error("Gagal melanjutkan scan:", err);
                                    });
                                }
                            } catch (err) {
                                console.error("Error saat resume scanner:", err);
                            }
                        }
                    }, 2000);
                }

                function startQRScanner() {
                    scanner = new Html5Qrcode("reader", {
                        verbose: false
                    });

                    const config = {
                        fps: 10,
                        qrbox: {
                            width: 250,
                            height: 250
                        },
                        aspectRatio: 1.0
                    };

                    scanner.start({
                            facingMode: "environment"
                        }, config, showScanResult)
                        .catch(err => {
                            alert("Gagal mengakses kamera: " + err);
                            console.error(err);
                        });
                }

                window.addEventListener('load', () => {
                    startQRScanner();
                    setGeolocation();
                });

                function fetchAngsuranData(noAnggota) {
                    fetch(`/api/anggota/${noAnggota}/pinjaman`)
                        .then(res => {
                            if (!res.ok) {
                                throw new Error(`HTTP error! status: ${res.status}`);
                            }
                            return res.json();
                        })
                        .then(data => {
                            if (!data.success || !data.pencairan.length) {
                                alert("Tidak ada pinjaman aktif untuk anggota ini.");
                                return;
                            }
                            renderPencairan(data.pencairan, data.anggota);
                        })
                        .catch(err => {
                            console.error('Fetch error:', err);
                            alert("Gagal mengambil data pinjaman: " + err.message);
                        });
                }

                function renderPencairan(pencairanList, anggota) {
                    pencairanList.forEach(pencairan => {
                        const existingItem = angsuranData.find(item => item.pencairan_id === pencairan.id);
                        if (existingItem) {
                            alert(
                                `Pinjaman ${anggota.nama} - Pinjaman ke-${pencairan.pinjaman_ke} sudah ada dalam daftar.`
                            );
                            return;
                        }

                        fetch(`/api/pencairan/${pencairan.id}/next-angsuran`)
                            .then(res => {
                                if (!res.ok) {
                                    throw new Error(`HTTP error! status: ${res.status}`);
                                }
                                return res.json();
                            })
                            .then(angsuranInfo => {
                                const div = document.createElement('div');
                                div.className = 'col-md-12 mb-2';
                                div.dataset.pencairanId = pencairan.id;

                                const nominalPencairan = parseFloat(pencairan.nominal) || 0;
                                const tenorValue = parseInt(pencairan.tenor) || 1;
                                const defaultNominal = tenorValue > 0 ?
                                    Math.floor((nominalPencairan + (nominalPencairan * 0.2)) / tenorValue) : 0;

                                div.innerHTML = `
                                    <div class="card border">
                                        <div class="card-body p-3">
                                            <div class="d-flex align-items-center justify-content-between">
                                                <!-- Info Anggota -->
                                                <div class="flex-grow-1 text-start">
                                                    <h6 class="mb-1">${anggota.nama}</h6>
                                                    <small class="text-muted">
                                                        ${anggota.no_anggota} | Pinjaman ke-${pencairan.pinjaman_ke} | 
                                                        Angsuran ke-${angsuranInfo.angsuran_ke}
                                                    </small>
                                                    <br>
                                                    <small class="text-info">
                                                        Sisa Kredit: ${formatToRupiah(pencairan.sisa_kredit)}
                                                    </small>
                                                </div>

                                                <!-- Input Nominal Angsuran -->
                                                <div class="mx-2" style="width: 160px;">
                                                    <input type="text" 
                                                        class="form-control form-control-sm numeric-input" 
                                                        placeholder="0" 
                                                        value="${formatRupiah(defaultNominal.toString())}"
                                                        maxlength="14"
                                                        oninput="handleInputChange(this)"
                                                        data-pencairan-id="${pencairan.id}"
                                                        data-angsuran-ke="${angsuranInfo.angsuran_ke}"
                                                        data-sisa-kredit="${pencairan.sisa_kredit}"
                                                        required>
                                                </div>

                                                <!-- Tombol Hapus -->
                                                <div class="ms-2">
                                                    <button class="btn btn-danger btn-sm remove-btn" type="button" title="Hapus">
                                                        <i class="ri-delete-bin-2-fill"></i>
                                                    </button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                `;

                                div.querySelector('.remove-btn').addEventListener('click', () => {
                                    angsuranData = angsuranData.filter(item => item.pencairan_id !== pencairan
                                        .id);
                                    div.remove();
                                    updateTotals();
                                });

                                angsuranList.appendChild(div);

                                angsuranData.push({
                                    pencairan_id: pencairan.id,
                                    angsuran_ke: angsuranInfo.angsuran_ke,
                                    nominal: defaultNominal,
                                    anggota_nama: anggota.nama,
                                    no_anggota: anggota.no_anggota,
                                    sisa_kredit: pencairan.sisa_kredit
                                });

                                updateTotals();
                            })
                            .catch(err => {
                                console.error('Error getting next angsuran:', err);
                                alert('Gagal mengambil informasi angsuran: ' + err.message);
                            });
                    });
                }

                function handleInputChange(input) {
                    const formattedValue = formatRupiah(input.value);
                    input.value = formattedValue;

                    const pencairanId = parseInt(input.dataset.pencairanId);
                    const nominal = parseRupiah(input.value);
                    const sisaKredit = parseInt(input.dataset.sisaKredit);

                    if (nominal > sisaKredit) {
                        alert('Nominal angsuran tidak boleh melebihi sisa kredit!');
                        input.value = formatRupiah(sisaKredit.toString());
                        return;
                    }

                    const dataIndex = angsuranData.findIndex(item => item.pencairan_id === pencairanId);
                    if (dataIndex !== -1) {
                        angsuranData[dataIndex].nominal = nominal;
                    }

                    updateTotals();
                }

                function formatRupiah(angka) {
                    let numberString = angka.replace(/[^0-9]/g, '');
                    if (numberString.length > 8) numberString = numberString.slice(0, 8);
                    return numberString.replace(/\B(?=(\d{3})+(?!\d))/g, ".");
                }

                function parseRupiah(rupiahString) {
                    if (!rupiahString || rupiahString.trim() === '') return 0;
                    return parseInt(rupiahString.replace(/\./g, '')) || 0;
                }

                function formatToRupiah(angka) {
                    return new Intl.NumberFormat('id-ID', {
                        style: 'currency',
                        currency: 'IDR',
                        minimumFractionDigits: 0,
                        maximumFractionDigits: 0
                    }).format(angka);
                }

                function updateTotals() {
                    const inputs = document.querySelectorAll('#angsuran-list .numeric-input');
                    let totalData = 0;
                    let totalNominal = 0;

                    inputs.forEach(input => {
                        const value = parseRupiah(input.value);
                        if (value > 0) {
                            totalData++;
                            totalNominal += value;
                        }
                    });

                    totalDataElement.textContent = totalData;
                    totalNominalElement.textContent = formatToRupiah(totalNominal);
                }

                let latitude = null;
                let longitude = null;

                function setGeolocation() {
                    if (navigator.geolocation) {
                        navigator.geolocation.getCurrentPosition(function(position) {
                            latitude = position.coords.latitude;
                            longitude = position.coords.longitude;
                        }, function(error) {
                            console.error('Error getting location:', error);
                        });
                    }
                }

                function submitMultipleAngsuran() {
                    if (angsuranData.length === 0) {
                        alert('Tidak ada data angsuran untuk disimpan!');
                        return;
                    }

                    const validData = angsuranData.filter(item => item.nominal > 0);

                    if (validData.length === 0) {
                        alert('Harap masukkan nominal angsuran yang valid!');
                        return;
                    }

                    if (!confirm(`Yakin ingin menyimpan ${validData.length} data angsuran?`)) {
                        return;
                    }

                    saveButton.disabled = true;
                    saveButton.innerHTML = '<i class="ri-loader-2-line"></i>';

                    const tanggalAngsuran = document.getElementById('tanggal-angsuran')?.value || new Date().toISOString().split(
                        'T')[0];

                    const submitData = {
                        angsuran_data: validData.map(item => ({
                            pencairan_id: parseInt(item.pencairan_id),
                            angsuran_ke: parseInt(item.angsuran_ke),
                            jenis_transaksi: '001 - Angsuran',
                            nominal: parseInt(item.nominal),
                            tanggal_angsuran: tanggalAngsuran,
                            latitude: latitude ? latitude.toString() : null,
                            longitude: longitude ? longitude.toString() : null
                        }))
                    };

                    fetch('/angsuran/store-multiple', {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                                'Accept': 'application/json'
                            },
                            body: JSON.stringify(submitData)
                        })
                        .then(response => {

                            if (!response.ok) {
                                return response.json().then(errorData => {
                                    console.log('Error response data:', errorData);
                                    throw new Error(
                                        `Validation Error: ${JSON.stringify(errorData.errors || errorData.message)}`
                                        );
                                }).catch(jsonError => {
                                    throw new Error(`HTTP error! status: ${response.status}`);
                                });
                            }

                            const contentType = response.headers.get('content-type');
                            if (!contentType || !contentType.includes('application/json')) {
                                throw new Error('Server tidak mengembalikan JSON response');
                            }

                            return response.json();
                        })
                        .then(data => {
                            if (data.success) {
                                alert('Data angsuran berhasil disimpan!');
                                angsuranData = [];
                                angsuranList.innerHTML = '';
                                updateTotals();
                                resultText.textContent = '-';
                            } else {
                                alert('Error: ' + (data.message || 'Gagal menyimpan data'));
                                if (data.errors && data.errors.length > 0) {
                                    console.log('Errors:', data.errors);
                                }
                            }
                        })
                        .catch(error => {
                            console.error('Submit error:', error);
                            alert('Terjadi kesalahan saat menyimpan data: ' + error.message);
                        })
                        .finally(() => {
                            // Enable kembali tombol save
                            saveButton.disabled = false;
                            saveButton.innerHTML = '<i class="ri-save-line"></i>';
                        });
                }

                saveButton.addEventListener('click', submitMultipleAngsuran);
                document.addEventListener('DOMContentLoaded', function() {
                    updateTotals();
                });
            </script>
        </x-slot>
    </x-bar.navbar>
@endsection
