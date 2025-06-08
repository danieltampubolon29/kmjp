@extends('layouts.aplication')
@section('title', 'Angsuran')
@section('content')
    <x-bar.navbar>Halaman Angsuran
        <x-slot name="content">
            <div class="container mt-4">
                <div class="row">

                    <div class="col-md-3 mb-5">
                        <div class="card shadow">
                            <div class="card-header bg-primary text-white">
                                <strong>QR Scanner</strong>
                            </div>
                            <div class="card-body text-center">
                                <div id="reader" style="width: 100%; height: auto;"></div>
                                <h5 class="mt-3">No Anggota Terdeteksi:</h5>
                                <p id="result-text" class="fs-4 text-success fw-bold mb-0"></p>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-9 mb-3">
                        <x-alert-message></x-alert-message>
                        <div class="card shadow">
                            <div class="card-header bg-primary d-flex justify-content-between align-items-center">
                                <h5 class="mb-0">Daftar Angsuran</h5>
                                <input type="date" class="form-control form-control-sm" style="width: auto;">
                            </div>
                            <div class="card-body">
                                <div id="angsuran-list" class="row g-3"></div>
                            </div>
                            <div class="card-footer bg-transparent border-top py-3">
                                <div class="d-flex align-items-center justify-content-between">
                                    <div class="flex-grow-1 text-start">
                                        <span class="text-muted">Total: <strong id="total-data">0</strong></span>
                                    </div>
                                    <div class="mx-2" style="width: 160px; text-align: center;">
                                        <span class="text-muted"><strong id="total-nominal">Rp 0</strong></span>
                                    </div>
                                    <div class="ms-2">
                                        <button id="reset-button" class="btn btn-success" type="button"
                                            title="Save">
                                            <i class="ri-save-line"></i>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Audio untuk beep -->
            <audio id="scan-sound">
                <source src="{{ asset('sounds/qr-scan-beep.mp3') }}" type="audio/mpeg">
                Browser Anda tidak mendukung audio.
            </audio>

            <!-- Script HTML5Qrcode -->
            <!-- Ganti CDN jika unpkg.com diblokir -->
            <script src="https://cdn.jsdelivr.net/npm/html5-qrcode@2.3.8/html5-qrcode.min.js"></script>

            <!-- Script Utama -->
            <script>
                const reader = document.getElementById('reader');
                const resultText = document.getElementById('result-text');
                const angsuranList = document.getElementById('angsuran-list');
                const totalDataElement = document.getElementById('total-data');
                const totalNominalElement = document.getElementById('total-nominal');

                let scanner = null;

                function showScanResult(data) {
                    // Putar suara beep
                    const scanSound = document.getElementById("scan-sound");
                    scanSound.play();

                    resultText.textContent = data;

                    if (scanner && scanner.isScanning) {
                        scanner.pause(); // Hentikan sementara
                    }

                    fetchAngsuranData(data);

                    // Otomatis lanjutkan scan setelah 2 detik
                    setTimeout(() => {
                        if (scanner) {
                            scanner.resume().catch(err => {
                                console.error("Gagal melanjutkan scan:", err);
                            });
                        }
                    }, 2000); // Jeda 2 detik
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
                });

                // Ambil data pinjaman berdasarkan no_anggota
                function fetchAngsuranData(noAnggota) {
                    fetch(`/api/anggota/${noAnggota}/pinjaman`)
                        .then(res => res.json())
                        .then(data => {
                            if (!data.success || !data.pencairan.length) {
                                alert("Tidak ada pinjaman aktif untuk anggota ini.");
                                return;
                            }
                            renderPencairan(data.pencairan, data.anggota.nama);
                        })
                        .catch(err => {
                            console.error(err);
                            alert("Gagal mengambil data pinjaman.");
                        });
                }

                // Tampilkan data pinjaman di card
                function renderPencairan(pencairanList, namaAnggota) {
                    pencairanList.forEach(pencairan => {
                        const div = document.createElement('div');
                        div.className = 'col-md-12';
                        div.innerHTML = `
                                <div class="d-flex align-items-center justify-content-between">
                                    <!-- Nama Anggota -->
                                    <div class="flex-grow-1 text-start">
                                        <h5 class="mb-0">${namaAnggota}</h5>
                                    </div>

                                    <!-- Input Nominal Angsuran -->
                                    <div class="mx-2" style="width: 160px;">
                                        <input type="text" 
                                               class="form-control form-control-sm numeric-input" 
                                               placeholder="0" 
                                               maxlength="14"
                                               oninput="handleInputChange(this)"
                                               data-original-value="0"
                                               required>
                                    </div>

                                    <!-- Tombol Hapus -->
                                    <div class="ms-2">
                                        <button class="btn btn-danger btn-sm remove-btn" type="button" title="Hapus">
                                            <i class="ri-delete-bin-2-fill"></i>
                                        </button>
                                    </div>
                                </div>
                    `;

                        // Event hapus
                        div.querySelector('.remove-btn').addEventListener('click', () => {
                            div.remove();
                            updateTotals(); // Update total setelah menghapus
                        });

                        angsuranList.appendChild(div);
                        updateTotals(); // Update total setelah menambah item
                    });
                }

                // Handle input change untuk format rupiah dan update total
                function handleInputChange(input) {
                    const formattedValue = formatRupiah(input.value);
                    input.value = formattedValue;
                    updateTotals();
                }

                // Format nominal ribuan
                function formatRupiah(angka) {
                    let numberString = angka.replace(/[^0-9]/g, '');
                    if (numberString.length > 8) numberString = numberString.slice(0, 8);
                    return numberString.replace(/\B(?=(\d{3})+(?!\d))/g, ".");
                }

                // Fungsi untuk mengkonversi format rupiah ke angka
                function parseRupiah(rupiahString) {
                    if (!rupiahString || rupiahString.trim() === '') return 0;
                    return parseInt(rupiahString.replace(/\./g, '')) || 0;
                }

                // Fungsi untuk memformat angka ke format rupiah dengan prefix
                function formatToRupiah(angka) {
                    return new Intl.NumberFormat('id-ID', {
                        style: 'currency',
                        currency: 'IDR',
                        minimumFractionDigits: 0,
                        maximumFractionDigits: 0
                    }).format(angka);
                }

                // Update total data dan total nominal
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

                    // Update tampilan
                    totalDataElement.textContent = totalData;
                    totalNominalElement.textContent = formatToRupiah(totalNominal);
                }

                // Inisialisasi total saat halaman dimuat
                document.addEventListener('DOMContentLoaded', function() {
                    updateTotals();
                });
            </script>
        </x-slot>
    </x-bar.navbar>
@endsection
