@extends('layouts.aplication')
@section('title', 'Laporan')
@section('content')
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="stylesheet" href="{{ asset('css/calender.css') }}">
    <style>
        #fixedTable {
            table-layout: fixed;
            width: 100%;
        }

        #fixedTable td {
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
            font-size: 14px;
        }

        .table-bordered .biru {
            background: rgba(116, 206, 233, 0.6);
        }

        @media (max-width: 768px) {
            #fixedTable td , .ikut{
                font-size: 10px;
            }
            #cardBody h5 {
                font-size: 20px;
            }
            
        }
    </style>
    <link rel="stylesheet"
        href="https://fonts.googleapis.com/css2?family=Material+Symbols+Rounded:opsz,wght,FILL,GRAD@20..48,100..700,0..1,-50..200">
    <x-bar.navbar>Laporan Harian
        <x-slot name="content">
            <div class="container mb-5">
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <div class="card">
                            <div class="card-header">Informasi</div>
                            <div class="card-body">
                                @if($kasbon == 0 || $kasbon == null)
                                    <p>Anda tidak memiliki kasbon lapangan dari kantor.</p>
                                @else
                                    <p>Kasbon lapangan Anda sebesar Rp. <strong>{{ number_format($kasbon, 0, ',', '.') }}</strong></p>
                                @endif
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
                                <div class="d-flex justify-content-between">
                                    <div class="left-column">
                                        <p><strong>Tanggal: </strong> <span id="selectedDate"></span></p>
                                        <p><strong>Hari: </strong> <span id="selectedDay"></span></p>
                                    </div>
                                    <div class="right-column">
                                        <p><strong>Marketing: </strong> <span
                                                id="marketingName">{{ Auth::user()->name }}</span></p>
                                    </div>
                                </div>
                                <div class="table-responsive">
                                    <table id="fixedTable" class="table table-bordered" style="border: 1px">
                                        <tbody>
                                            <tr>
                                                <td>Dari Kantor</td>
                                                <td class="biru"></td>
                                                <td></td>
                                            </tr>
                                            <tr>
                                                <td>Angsuran</td>
                                                <td class="text-center" id="angsuran"></td>
                                                <td class="biru"></td>
                                            </tr>
                                            <tr>
                                                <td>Tabungan</td>
                                                <td class="text-center" id="tabunganTop"></td>
                                                <td class="biru"></td>
                                            </tr>
                                            <tr>
                                                <td>Administrasi</td>
                                                <td class="text-center" id="administrasi"></td>
                                                <td class="biru"></td>
                                            </tr>
                                            <tr>
                                                <td>Total Penerimaan</td>
                                                <td class="biru"></td>
                                                <td></td>
                                            </tr>
                                            <tr>
                                                <td>Pencairan</td>
                                                <td class="text-center" id="pencairan"></td>
                                                <td class="biru"></td>
                                            </tr>
                                            <tr>
                                                <td>Trf Ke Kantor</td>
                                                <td></td>
                                                <td class="biru"></td>
                                            </tr>
                                            <tr>
                                                <td>Tabungan</td>
                                                <td class="text-center" id="tabunganBottom"></td>
                                                <td class="biru"></td>
                                            </tr>
                                            <tr>
                                                <td>Lainnya</td>
                                                <td></td>
                                                <td class="biru"></td>
                                            </tr>
                                            <tr>
                                                <td>Total Pengeluaran</td>
                                                <td class="biru"></td>
                                                <td></td>
                                            </tr>
                                            <tr>
                                                <td><strong>Saldo Akhir</strong></td>
                                                <td class="biru"></td>
                                                <td></td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                                <div class="row mt-5">
                                    <div class="col-6 text-center">
                                        <strong class="ikut">Marketing</strong>
                                        <div class="signature-box mt-3">
                                            <span class="bracket">
                                                &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                            </span>
                                        </div>
                                        <p class="mt-3 ikut" id="marketingName">{{ Auth::user()->name }}</p>
                                    </div>
                                    <div class="col-6 text-center">
                                        <strong class="ikut">Koordinator</strong>
                                        <div class="signature-box mt-3">
                                            <span class="bracket">
                                                &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                            </span>
                                        </div>
                                        <p class="mt-3 ikut">_ _ _ _ _ _ _ _ _</p>
                                    </div>
                                </div>
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
