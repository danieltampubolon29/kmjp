@extends('layouts.aplication')
@section('title', 'Laporan')
@section('content')
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="stylesheet" href="{{ asset('css/calender.css') }}">
    <link rel="stylesheet"
        href="https://fonts.googleapis.com/css2?family=Material+Symbols+Rounded:opsz,wght,FILL,GRAD@20..48,100..700,0..1,-50..200">
    <x-bar.navbar>Laporan Harian
        <x-slot name="content">
            <div class="container mb-5">
                <div class="row">
                    <div class="col-md-6">
                        <div class="card">
                            <div class="card-header">Informasi</div>
                            <div class="card-body">
                                <p><strong>Marketing: </strong> <span id="marketingName">{{ Auth::user()->name }}</span></p>
                                <p><strong>Tanggal: </strong> <span id="selectedDate">-</span></p>
                                <p><strong>Hari: </strong> <span id="selectedDay">-</span></p>
                                <p><strong>Kasbon: </strong>Rp. {{ number_format($kasbon, 0, ',', '.') }}</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="card">
                            <div class="card-header d-flex justify-content-between align-items-center">
                                <button class="btn btn-primary" id="downloadBtn">
                                    <i class="ri-download-2-line"></i>
                                </button>
                                <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#dateModal">
                                    Pilih Tanggal
                                </button>
                            </div>
                            <div class="card-body" id="cardBody">
                                <div class="text-center mt-3 mb-3">
                                    <h5 class="fw-bold">KAS HARIAN MARKETING</h5>
                                </div>
                                <table class="table table-bordered">
                                    <tbody>
                                        <tr>
                                            <td>Dari Kantor</td>
                                            <td></td>
                                            <td>{{ number_format($kasbon, 0, ',', '.') }}</td>
                                        </tr>
                                        <tr>
                                            <td>Angsuran</td>
                                            <td></td>
                                            <td></td>
                                        </tr>
                                        <tr>
                                            <td>Tabungan</td>
                                            <td></td>
                                            <td></td>
                                        </tr>
                                        <tr>
                                            <td>Administrasi</td>
                                            <td></td>
                                            <td></td>
                                        </tr>
                                        <tr>
                                            <td><strong>Total Penerimaan</strong></td>
                                            <td></td>
                                            <td></td>
                                        </tr>
                                        <tr>
                                            <td>Pencairan</td>
                                            <td></td>
                                            <td></td>
                                        </tr>
                                        <tr>
                                            <td>Trf Ke Kantor</td>
                                            <td></td>
                                            <td></td>
                                        </tr>
                                        <tr>
                                            <td>Tabungan</td>
                                            <td></td>
                                            <td></td>
                                        </tr>
                                        <tr>
                                            <td><strong>Total Pengeluaran</strong></td>
                                            <td></td>
                                            <td></td>
                                        </tr>
                                        <tr>
                                            <td><strong>Saldo Akhir</strong></td>
                                            <td></td>
                                            <td></td>
                                        </tr>
                                    </tbody>
                                </table>
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
            <script src="{{ asset('js/laporan/harian.js') }}"></script>
            <script src="https://cdnjs.cloudflare.com/ajax/libs/html2canvas/1.4.1/html2canvas.min.js"></script>
            <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>
        </x-slot>
    </x-bar.navbar>
@endsection
