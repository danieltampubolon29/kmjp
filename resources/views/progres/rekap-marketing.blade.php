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
                        <button class="btn btn-primary" id="rekapUtama">
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
                    <div class="card-body card-body1">
                        <div id="judulLaporan" class="text-center fw-bold fs-5 mb-2">
                        </div>

                        <div class="table-responsive">
                            <table class="table table-bordered table-sm text-center align-middle">
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
            <script src="{{ asset('js/progres/rekap-marketing.js') }}"></script>
        </x-slot>
    </x-bar.navbar>
@endsection
