    @extends('layouts.aplication')
    @section('title', 'Laporan')
    @section('content')
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <link rel="stylesheet" href="{{ asset('css/calender.css') }}">
        <link rel="stylesheet"
            href="https://fonts.googleapis.com/css2?family=Material+Symbols+Rounded:opsz,wght,FILL,GRAD@20..48,100..700,0..1,-50..200">
        <x-bar.navbar>Laporan Pencairan
            <x-slot name="content">
                <div class="container mb-5">
                    <div class="card">
                        <div class="card-header d-flex justify-content-between align-items-center">
                            <button class="btn btn-primary" id="downloadBtn">
                                <i class="ri-download-2-line"></i>
                            </button>
                            <div class="d-flex gap-2">
                                <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#dateModal">
                                    Pilih Tanggal
                                </button>
                            </div>
                        </div>
                        <div class="card-body" id="cardBody">
                            <div class="text-center mt-5 mb-3">
                                <h5 class="fw-bold">LAPORAN PENCAIRAN HARIAN MARKETING</h5>
                            </div>
                            <div class="d-flex justify-content-between mb-3">
                                <div>
                                    <strong>Marketing:</strong> <span id="marketingName">{{ Auth::user()->name }}</span>
                                </div>
                                <div>
                                    <strong>Tanggal:</strong> <span id="selectedDate">-</span>
                                </div>
                                <div>
                                    <strong>Hari:</strong> <span id="selectedDay">-</span>
                                </div>
                            </div>
                            <div class="table-responsive">
                                <table id="dynamicTable" class="table table-bordered">
                                    <thead>
                                        <tr>
                                            <th>NO</th>
                                            <th>Nama</th>
                                            <th>Nominal</th>
                                            <th>Admin</th>
                                            <th>Simpanan</th>
                                            <th>Diterima</th>
                                            <th>Tenor</th>
                                            <th>NO ANGGOTA</th>
                                        </tr>
                                    </thead>
                                    <tbody id="tableBody"></tbody>
                                    <tfoot>
                                        <tr>
                                            <td colspan="2" class="text-start">Total</td>
                                            <td id="totalPencairan">-</td>
                                            <td id="totalAdmin">-</td>
                                            <td id="totalSimpanan">-</td>
                                            <td id="totalDiterima">-</td>
                                            <td colspan="2"></td>
                                        </tr>
                                    </tfoot>
                                </table>
                            </div>
                            <div class="row mt-5">
                                <div class="col-6 text-center">
                                    <strong>Marketing</strong>
                                    <div class="signature-box mt-5">
                                        <span class="bracket">
                                            &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                        </span>
                                    </div>
                                    <p class="mt-3" id="marketingName">{{ Auth::user()->name }}</p>
                                </div>
                                <div class="col-6 text-center">
                                    <strong>Koordinator</strong>
                                    <div class="signature-box mt-5">
                                        <span class="bracket">
                                            &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                        </span>
                                    </div>
                                    <p class="mt-3">_ _ _ _ _ _ _ _ _</p>
                                </div>
                            </div>
                        </div>
                        <div class="card-footer">
                            <div class="row d-flex align-items-center">
                                <div class="col-6 d-flex align-items-center">
                                    <button class="btn btn-danger h-100" id="resetButton">
                                        <i class="ri-refresh-line me-1"></i> Reset
                                    </button>
                                </div>
                                <div class="col-6 d-flex justify-content-end align-items-center">
                                    <nav aria-label="Page navigation">
                                        <ul class="pagination mb-0" id="pagination"></ul>
                                    </nav>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal fade" id="dateModal" tabindex="-1" aria-labelledby="dateModalLabel" aria-hidden="true">
                    <div class="modal-dialog modal-dialog-centered modal-lg">
                        <div class="modal-content">
                            <div class="modal-body p-0">
                                <div class="wrapper">
                                    <header>
                                        <p class="current-date"></p>
                                        <div class="icons">
                                            <span id="prev" class="material-symbols-rounded">chevron_left</span>
                                            <span id="next" class="material-symbols-rounded">chevron_right</span>
                                        </div>
                                    </header>
                                    <div class="calendar">
                                        <ul class="weeks">
                                            <li>Min</li>
                                            <li>Sen</li>
                                            <li>Sel</li>
                                            <li>Rab</li>
                                            <li>Kam</li>
                                            <li>Jum</li>
                                            <li>Sab</li>
                                        </ul>
                                        <ul class="days"></ul>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <script src="{{ asset('js/laporan/pencairan.js') }}"></script>
                <script src="https://cdnjs.cloudflare.com/ajax/libs/html2canvas/1.4.1/html2canvas.min.js"></script>
                <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>
            </x-slot>
        </x-bar.navbar>
    @endsection
