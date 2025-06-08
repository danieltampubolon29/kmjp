@extends('layouts.aplication')
@section('title', 'Dashboard')
@section('content')
    <x-bar.navbar> CEK DATA
        <link rel="stylesheet" href="{{ asset('css/dashboard/marketing.css') }}">
        <x-slot name="content">
            <div class="container mt-4">
                <div class="card">
                    <div class="bg-primary card-header d-flex justify-content-between align-items-center">
                        <h5 class="card-title mt-2 text-light"></h5>
                        <form action="" method="" class="d-flex position-relative">
                            <div class="input-group">
                                <input type="text" id="searchInput" name="search" class="form-control" placeholder="Cari Anggota" />
                            </div>
                            <ul id="searchResults" class="list-group position-absolute w-100"
                                style="z-index: 1000; display: none; max-height: 355px; overflow-y: auto;">
                            </ul>
                        </form>
                    </div>
                    <div class="card-body">
                        <div class="row mb-4">
                            <div class="col-md-12">
                                <div class="data-row">
                                    <span class="data-label">Nama Anggota</span>
                                    <span class="data-value" id="namaAnggota">-</span>
                                </div>
                                <div class="data-row">
                                    <span class="data-label">Nomor Anggota</span>
                                    <span class="data-value" id="noAnggota">-</span>
                                </div>
                            </div>
                        </div>
                        <h4 class="card-subtitle mb-2">SIMPANAN</h4>
                        <div class="row mb-4 p-2">
                            <div class="col-3 text-center">
                                <p class="fw-bold text-truncate" style="font-size: 0.9rem; white-space: nowrap;">POKOK</p>
                                <p id="simpananPokok" class="simpanan-value">-</p>
                            </div>
                            <div class="col-3 text-center">
                                <p class="fw-bold text-truncate" style="font-size: 0.9rem; white-space: nowrap;">WAJIB</p>
                                <p id="simpananWajib" class="simpanan-value">-</p>
                            </div>
                            <div class="col-3 text-center">
                                <p class="fw-bold text-truncate" style="font-size: 0.9rem; white-space: nowrap;">SUKARELA
                                </p>
                                <p id="simpananSukarela" class="simpanan-value">-</p>
                            </div>
                            <div class="col-3 text-center">
                                <p class="fw-bold text-truncate" style="font-size: 0.9rem; white-space: nowrap;">DEPOSITO
                                </p>
                                <p id="simpananDeposito" class="simpanan-value">-</p>
                            </div>
                        </div>
                        <h4 class="card-subtitle mb-2">PINJAMAN</h4>
                        <div class="table-responsive mb-4">
                            <table class="table table-light table-hover text-center pinjaman-table">
                                <thead class="bg-light">
                                    <tr>
                                        <th class="text-center align-middle">PINJAMAN</th>
                                        <th class="text-center align-middle">TANGGAL</th>
                                        <th class="text-center align-middle">NOMINAL</th>
                                        <th class="text-center align-middle">SISA KREDIT</th>
                                    </tr>
                                </thead>
                                <tbody>
                                </tbody>
                            </table>
                        </div>
                        <div class="card-footer d-flex justify-content-end">
                            <button type="button" class="btn btn-danger me-2" onclick="resetForm()">
                                <i class="ri-refresh-line"></i>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
            @include('marketing.modal')
            <script src="{{ asset('js/dashboard/marketing/cek-data.js') }}"></script>
            <script src="{{ asset('js/dashboard/marketing/reset.js') }}"></script>
        </x-slot>
    </x-bar.navbar>
@endsection
