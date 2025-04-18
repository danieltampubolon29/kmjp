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
                .card-footer .col-1,
                {
                padding: 0.5rem;
                display: flex;
                align-items: center;
                }
            </style>
            <div class="py-4 px-3">
                <div class="row d-flex align-items-stretch">
                    <div class="col-lg-4 mt-3">
                        <div class="card p-3 shadow-sm h-100">
                            <h5 class="mb-3">Informasi Kasbon</h5>
                            <div class="row">
                                <div class="col-6">Kasbon Progres</div>
                                <div class="col-1 text-center">:</div>
                                <div class="col-5 text-end">Rp {{ number_format($progres, 0, ',', '.') }}</div>

                                <div class="col-6">Pengambilan </div>
                                <div class="col-1 text-center">:</div>
                                <div class="col-5 text-end" id="pengambilanKasbon"></div>

                                <div class="col-6">Pengembalian </div>
                                <div class="col-1 text-center">:</div>
                                <div class="col-5 text-end" id="pengembalianKasbon"></div>

                                <div class="col-6" id="kasbonBulanIni">Kasbon </div>
                                <div class="col-1 text-center">:</div>
                                <div class="col-5 text-end" id="kasbonPerBulan"></div>

                            </div>
                            <div class="card-footer bg-transparent border-0 mt-auto">
                                <div class="row">
                                    <div class="col-6"><strong>Total Kasbon</strong></div>
                                    <div class="col-1 text-center"><strong>:</strong></div>
                                    <div class="col-5 text-end">
                                        <strong>Rp {{ number_format($totalKasbon, 0, ',', '.') }}</strong>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-8 mt-3">
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
            <script src="{{ asset('js/progres/rekap-data.js') }}"></script>
        </x-slot>
    </x-bar.navbar>
@endsection
