@extends('layouts.aplication')
@section('title', 'Progres')
@section('content')
    <x-bar.navbar>Rekap Data
        <x-slot name="content">
            <style>
                .card-footer {
                    padding: 0;
                    margin-top: auto;
                }

                .card-footer .row {
                    align-items: center;
                }

                .card-footer .col-6,
                .card-footer .col-1 {
                    padding: 0.5rem;
                    display: flex;
                    align-items: center;
                }
            </style>


            <div class="container my-4">
                <div class="card">
                    <div class="card-header d-flex justify-content-between align-items-center text-dark">
                        <!-- Tombol di kiri -->
                        <div class="d-flex gap-2">
                            <button class="btn btn-primary" id="rekapUtama">
                                <i class="ri-download-2-line"></i>
                            </button>
                            <button class="btn btn-success" id="exportExcelBtn">
                                <i class="ri-file-excel-2-line"></i>
                            </button>
                        </div>

                        <!-- Input Bulan & Tahun di kanan -->
                        <div class="d-flex gap-2">
                            <select id="monthSelect" class="form-select">
                                @for ($i = 1; $i <= 12; $i++)
                                    <option value="{{ $i }}">
                                        {{ date('F', mktime(0, 0, 0, $i, 1)) }}
                                    </option>
                                @endfor
                            </select>
                            <input type="number" id="yearInput" class="form-control" placeholder="Tahun" min="2000"
                                max="2100" />
                        </div>
                    </div>
                    <div class="card-body card-body1">
                        <div id="judulLaporan" class="text-center fw-bold fs-5 mb-2">
                        </div>

                        <div class="table-responsive">
                            <table id="tableRekapUtama" class="table table-bordered table-sm text-center align-middle">
                                <thead class="table-light">
                                    <tr>
                                        <th rowspan="2" class="align-middle">Marketing</th>
                                        <th colspan="2">Pencairan</th>
                                        <th colspan="3">Data Pinjaman</th>
                                        <th colspan="2">Data Angsuran</th>
                                    </tr>
                                    <tr>
                                        <th>Nasabah Baru</th>
                                        <th>Nominal Pencairan</th>
                                        <th>Saldo Awal </th>
                                        <th>Pencairan Aktif</th>
                                        <th>Sisa Saldo </th>
                                        <th>Nasabah bayar</th>
                                        <th>Nominal Angsuran</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <!-- Data Marketing -->
                                    <tr class="fw-bold">
                                        <td class="text-start">TOTAL</td>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                    </tr>
                                </tbody>
                            </table>
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
                        <h5 class="card-title mb-0 flex-grow-1 text-end"></h5>
                    </div>
                    <div class="card-body card-body2">
                        <div class="table-responsive">
                            <table id="dynamicTable" class="table table-bordered">
                                <thead id="tableHeader">
                                    <tr></tr>
                                    <tr id="tableSubHeader"></tr>
                                </thead>
                                <tbody id="tableBody"></tbody>
                                <tfoot id="tableFooter">
                                    <tr></tr>
                                </tfoot>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

            <script src="https://cdnjs.cloudflare.com/ajax/libs/html2canvas/1.4.1/html2canvas.min.js"></script>
            <script src="https://cdnjs.cloudflare.com/ajax/libs/xlsx/0.18.5/xlsx.full.min.js"></script>
            <script src="{{ asset('js/progres/rekap-marketing.js') }}"></script>
            <script>
                function exportBothTablesToExcel() {
                    const {
                        month,
                        year,
                        bulanText
                    } = getSelectedMonthYear();

                    // Ambil tabel dari DOM
                    const tableRekapUtama = document.getElementById("tableRekapUtama");
                    const tableProgres = document.getElementById("dynamicTable");

                    if (!tableRekapUtama || !tableProgres) {
                        alert("Tabel tidak ditemukan!");
                        return;
                    }

                    // Konversi tabel ke worksheet
                    const wsRekapUtama = XLSX.utils.table_to_sheet(tableRekapUtama);
                    const wsProgres = XLSX.utils.table_to_sheet(tableProgres);

                    // Cari semua sel tanggal di tabel Progres dan ubah format
                    for (let cell in wsProgres) {
                        if (wsProgres[cell].t === 'n' && !isNaN(wsProgres[cell].v)) {
                            const value = wsProgres[cell].v;
                            if (value >= 1 && value <= 31) {
                                // Jika nilainya angka antara 1–31 dan di kolom pertama (tanggal)
                                wsProgres[cell].t = 's'; // Ganti ke string
                                wsProgres[cell].v = `${value}/${month}/${year.toString().slice(2)}`;
                            }
                        }
                    }

                    // Buat workbook baru dan tambahkan worksheet
                    const wb = XLSX.utils.book_new();
                    XLSX.utils.book_append_sheet(wb, wsRekapUtama, "Rekap Utama");
                    XLSX.utils.book_append_sheet(wb, wsProgres, "Progres Harian");

                    // Simpan file
                    XLSX.writeFile(wb, `Rekap-${bulanText}-${year}.xlsx`);
                }
                document.getElementById("exportExcelBtn").addEventListener("click", function() {
                    exportBothTablesToExcel();
                });
            </script>
        </x-slot>
    </x-bar.navbar>
@endsection
