@extends('layouts.aplication')

@section('title', 'Progres')

@section('content')
    <x-bar.navbar>Halaman Progres
        <x-slot name="content">
            <div class="py-4 px-3">
                <div class="row d-flex align-items-stretch">
                    <div class="col-lg-3 mt-3">
                        <div class="card p-3 shadow-sm h-100">
                            <h5 class="mb-3">Informasi</h5>
                            <p>Selamat datang di dashboard koperasi. Anda dapat melihat ringkasan data di sini.</p>
                        </div>
                    </div>
                    <div class="col-lg-9 mt-3">
                        <div class="row g-3">
                            <div class="col-12 col-sm-6">
                                <a href="#"
                                    class="text-dark text-decoration-none bg-white p-3 rounded shadow-sm d-flex justify-content-between summary-primary">
                                    <div>
                                        <i class="ri-user-fill summary-icon bg-primary mb-2"></i>
                                        <div>Jumlah Nasabah</div>
                                    </div>
                                    <h5 id="totalAnggota">0</h5>
                                </a>
                            </div>
                            <div class="col-12 col-sm-6">
                                <a href="#"
                                    class="text-dark text-decoration-none bg-white p-3 rounded shadow-sm d-flex justify-content-between summary-danger">
                                    <div>
                                        <i class="fa-solid fa-wallet summary-icon bg-danger mb-2"></i>
                                        <div>Saldo Simpanan</div>
                                    </div>
                                    <h5 id="saldoSimpanan">Rp. 0</h5>
                                </a>
                            </div>
                            <div class="col-12 col-sm-6">
                                <a href="#"
                                    class="text-dark text-decoration-none bg-white p-3 rounded shadow-sm d-flex justify-content-between summary-indigo">
                                    <div>
                                        <i class="fa-solid fa-money-bill-transfer summary-icon bg-indigo mb-2"></i>
                                        <div>Saldo Berjalan</div>
                                    </div>
                                    <h5 id="totalSisaKredit">Rp. 0</h5>
                                </a>
                            </div>
                            <div class="col-12 col-sm-6">
                                <a href="#"
                                    class="text-dark text-decoration-none bg-white p-3 rounded shadow-sm d-flex justify-content-between summary-success">
                                    <div>
                                        <i class="fa-solid fa-money-bill summary-icon bg-success mb-2"></i>
                                        <div>Pencairan Aktif</div>
                                    </div>
                                    <h5 id="totalPencairanPending">0</h5>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="container mb-5">
                <div class="card">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <button class="btn btn-primary" id="downloadBtn">
                            <i class="ri-download-2-line"></i>
                        </button>
                        <div class="d-flex gap-2">
                            <select id="monthSelect" class="form-select">
                                @for ($i = 1; $i <= 12; $i++)
                                    <option value="{{ $i }}">{{ date('F', mktime(0, 0, 0, $i, 1)) }}</option>
                                @endfor
                            </select>
                            <input type="number" id="yearInput" class="form-control" placeholder="Tahun" min="2000"
                                max="2100" />
                        </div>
                    </div>
                    <div class="card-body ">
                        <div class="table-responsive">
                            <table id="dynamicTable" class="table table-bordered">
                                <thead>
                                    <tr>
                                        <th>Tanggal</th>
                                        <th>Pencairan</th>
                                        <th>Angsuran</th>
                                    </tr>
                                </thead>
                                <tbody>
                                </tbody>
                                <tfoot>
                                    <tr>
                                        <td><strong>Total</strong></td>
                                        <td class="fw-bold" id="totalPencairan">-</td>
                                        <td class="fw-bold" id="totalAngsuran">-</td>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

            <script src="https://cdnjs.cloudflare.com/ajax/libs/html2canvas/1.4.1/html2canvas.min.js"></script>
            <script>
                function getDaysInMonth(year, month) {
                    return new Date(year, month, 0).getDate();
                }

                function formatCurrency(amount) {
                    if (!amount || amount === '-') return '-';
                    return amount.toString().replace(/\B(?=(\d{3})+(?!\d))/g, '.');
                }

                function generateTable() {
                    const month = parseInt(document.getElementById('monthSelect').value);
                    const year = parseInt(document.getElementById('yearInput').value);

                    if (!year || year < 2000 || year > 2100) {
                        alert('Masukkan tahun yang valid (2000-2100).');
                        return;
                    }

                    fetch(`/progres/get-pencairan-data?month=${month}&year=${year}`)
                        .then(response => {
                            if (!response.ok) {
                                throw new Error(`HTTP error! Status: ${response.status}`);
                            }
                            return response.json();
                        })
                        .then(data => {
                            console.log('Data received:', data);

                            // Update summary data
                            document.getElementById('totalAnggota').textContent = data.totalAnggota;
                            document.getElementById('saldoSimpanan').textContent = `Rp. ${formatCurrency(data.saldoSimpanan)}`;
                            document.getElementById('totalSisaKredit').textContent =
                                `Rp. ${formatCurrency(data.totalSisaKredit)}`;
                            document.getElementById('totalPencairanPending').textContent = data.totalPencairanPending;

                            // Generate table data
                            const daysInMonth = getDaysInMonth(year, month);
                            const tbody = document.querySelector('#dynamicTable tbody');
                            tbody.innerHTML = '';

                            for (let day = 1; day <= daysInMonth; day++) {
                                const row = document.createElement('tr');
                                const dateKey = `${year}-${String(month).padStart(2, '0')}-${String(day).padStart(2, '0')}`;
                                const dateCell = document.createElement('td');
                                dateCell.textContent = `${day}/${month}/${year}`;
                                row.appendChild(dateCell);

                                const pencairanAmount = data.pencairan_data[dateKey]?.total || 0;
                                const pencairanCell = document.createElement('td');
                                pencairanCell.textContent = formatCurrency(pencairanAmount);
                                row.appendChild(pencairanCell);

                                const angsuranAmount = data.angsuran_data[dateKey]?.total || 0;
                                const angsuranCell = document.createElement('td');
                                angsuranCell.textContent = formatCurrency(angsuranAmount);
                                row.appendChild(angsuranCell);

                                tbody.appendChild(row);
                            }

                            document.getElementById('totalPencairan').textContent = formatCurrency(data.total_pencairan);
                            document.getElementById('totalAngsuran').textContent = formatCurrency(data.total_angsuran);
                        })
                        .catch(error => {
                            console.error('Error fetching data:', error);
                            alert('Gagal memuat data dari server.');
                        });
                }

                document.getElementById('monthSelect').addEventListener('change', generateTable);
                document.getElementById('yearInput').addEventListener('input', generateTable);

                document.getElementById('downloadBtn').addEventListener('click', function() {
                    const cardBody = document.querySelector('.card-body');
                    html2canvas(cardBody).then(canvas => {
                        const imgData = canvas.toDataURL('image/png');
                        const a = document.createElement('a');
                        a.href = imgData;
                        a.download = 'progres.png';
                        a.click();
                    });
                });

                window.onload = function() {
                    const today = new Date();
                    const currentMonth = today.getMonth() + 1;
                    const currentYear = today.getFullYear();
                    document.getElementById('monthSelect').value = currentMonth;
                    document.getElementById('yearInput').value = currentYear;
                    generateTable();
                };
            </script>
        </x-slot>
    </x-bar.navbar>
@endsection
