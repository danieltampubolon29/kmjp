@extends('layouts.aplication')
@section('title', 'Progres')
@section('content')
    <x-bar.navbar>Halaman Progres
        <x-slot name="content">
            <div class="container">
                <div class="card">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <button class="btn btn-primary" id="downloadBtn"> 
                            <i class="ri-download-2-line"></i>
                        </button>
                        <div class="d-flex gap-2">
                            <select id="monthSelect" class="form-select">
                                <option value="1">Januari</option>
                                <option value="2">Februari</option>
                                <option value="3">Maret</option>
                                <option value="4">April</option>
                                <option value="5">Mei</option>
                                <option value="6">Juni</option>
                                <option value="7">Juli</option>
                                <option value="8">Agustus</option>
                                <option value="9">September</option>
                                <option value="10">Oktober</option>
                                <option value="11">November</option>
                                <option value="12">Desember</option>
                            </select>
                            <input type="number" id="yearInput" class="form-control" placeholder="Tahun" min="2000" max="2100" />
                        </div>
                    </div>
                    <div class="card-body">
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
                    return ` ${amount.toString().replace(/\B(?=(\d{3})+(?!\d))/g, '.')}`;
                }

                function generateTable() {
                    const month = parseInt(document.getElementById('monthSelect').value);
                    const year = parseInt(document.getElementById('yearInput').value);

                    if (!year || year < 2000 || year > 2100) {
                        alert('Masukkan tahun yang valid (2000-2100).');
                        return;
                    }

                    const daysInMonth = getDaysInMonth(year, month);
                    const tbody = document.querySelector('#dynamicTable tbody');
                    tbody.innerHTML = ''; 

                    fetch(`/progres/get-pencairan-data?month=${month}&year=${year}`)
                        .then(response => {
                            if (!response.ok) {
                                throw new Error(`HTTP error! Status: ${response.status}`);
                            }
                            return response.json();
                        })
                        .then(data => {
                            console.log('Data received:', data); 

                            const pencairanData = data.pencairan_data; 
                            const totalPencairan = data.total_pencairan; 
                            const angsuranData = data.angsuran_data; 
                            const totalAngsuran = data.total_angsuran; 

                            for (let day = 1; day <= daysInMonth; day++) {
                                const row = document.createElement('tr');

                                const dateKey = `${year}-${String(month).padStart(2, '0')}-${String(day).padStart(2, '0')}`;

                                const dateCell = document.createElement('td');
                                dateCell.textContent = `${day}/${month}/${year}`;
                                row.appendChild(dateCell);

                                const pencairanAmount = pencairanData[dateKey]?.total || 0;
                                const pencairanCell = document.createElement('td');
                                pencairanCell.textContent = formatCurrency(pencairanAmount);
                                row.appendChild(pencairanCell);

                                const angsuranAmount = angsuranData[dateKey]?.total || 0;
                                const angsuranCell = document.createElement('td');
                                angsuranCell.textContent = formatCurrency(angsuranAmount);
                                row.appendChild(angsuranCell);

                                tbody.appendChild(row);
                            }

                            document.getElementById('totalPencairan').textContent = formatCurrency(totalPencairan);
                            document.getElementById('totalAngsuran').textContent = formatCurrency(totalAngsuran);
                        })
                        .catch(error => {
                            console.error('Error fetching data:', error);
                            alert('Gagal memuat data dari server.');
                        });
                }

                document.getElementById('monthSelect').addEventListener('change', generateTable);
                document.getElementById('yearInput').addEventListener('input', generateTable);
                document.getElementById('downloadBtn').addEventListener('click', function () {
                    const cardBody = document.querySelector('.card-body');
                    html2canvas(cardBody).then(canvas => {
                        const imgData = canvas.toDataURL('image/png');
                        const a = document.createElement('a');
                        a.href = imgData;
                        a.download = 'progres.png'; 
                        a.click(); 
                    });
                });
                window.onload = function () {
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