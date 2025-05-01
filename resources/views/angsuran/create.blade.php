@extends('layouts.aplication')
@section('title', 'Angsuran')
@section('content')
    <x-bar.navbar>Halaman Angsuran
        <x-slot name="content">
            <div class="container mt-4">
                <x-alert-message></x-alert-message>
                <div class="card shadow">
                    <form class="form-submit" id="resetForm" method="POST" action="{{ route('angsuran.store') }}">
                        @csrf
                        <div class="card-header bg-primary text-white">
                            <h5 class="mb-0">Form Tambah Angsuran</h5>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-6 mb-3 position-relative">
                                    <label for="searchInput" class="form-label">Cari Pencairan</label>
                                    <input type="text" id="searchInput" class="form-control"
                                        placeholder="Ketik nama atau no anggota">
                                    <div id="searchResults" class="position-absolute w-100 overflow-auto"
                                        style="max-height: 200px; display: none; z-index: 1000;"></div>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label for="jenis_transaksi" class="form-label">Jenis Transaksi</label>
                                    <select id="jenis_transaksi" name="jenis_transaksi" class="form-control" required>
                                        <option value="001 - Angsuran">001 - Angsuran</option>
                                        <?php
                                            if (Auth::user()->role !== "marketing"): ?>
                                        <option value="009 - Pemutihan">009 - Pemutihan</option>
                                        <?php endif; ?>
                                    </select>
                                </div>

                            </div>
                            <input type="hidden" name="pencairan_id" id="pencairan_id" value="{{ $pencairanId ?? '' }}">
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="angsuran_ke" class="form-label">Angsuran Ke</label>
                                    <input type="text" id="angsuran_ke" name="angsuran_ke" class="form-control" readonly>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="nominal" class="form-label">Nominal Angsuran</label>
                                    <input type="text" id="nominal" name="nominal" class="form-control" required>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label for="tanggal_angsuran" class="form-label">Tanggal Angsuran</label>
                                    <input type="date" id="tanggal_angsuran" name="tanggal_angsuran" class="form-control"
                                        value="{{ date('Y-m-d') }}" required>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="no_anggota" class="form-label">No Anggota</label>
                                    <input type="text" id="no_anggota" class="form-control" readonly>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label for="nama" class="form-label">Nama Anggota</label>
                                    <input type="text" id="nama" class="form-control" readonly>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="pinjaman_ke" class="form-label">Pinjaman Ke</label>
                                    <input type="text" id="pinjaman_ke" class="form-control" readonly>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label for="produk" class="form-label">Produk</label>
                                    <input type="text" id="produk" class="form-control" readonly>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="tenor" class="form-label">Tenor</label>
                                    <input type="text" id="tenor" class="form-control" readonly>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label for="sisa_kredit" class="form-label">Sisa Kredit</label>
                                    <input type="text" id="sisa_kredit" class=" text-danger form-control" readonly>
                                </div>
                            </div>
                            <input type="hidden" name="latitude" id="latitude">
                            <input type="hidden" name="longitude" id="longitude">
                        </div>
                        <div class="card-footer bg-light d-flex justify-content-end">
                            <a href="{{ route('pencairan.index') }}" class="btn btn-secondary me-2">
                                <i class="ri-home-4-line"></i>
                            </a>
                            <button type="button" class="btn btn-danger me-2" onclick="resetForm()">
                                <i class="ri-refresh-line"></i>
                            </button>
                            <button type="submit" class="btn btn-success">
                                <i class="ri-save-line"></i>
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            <script src="{{ asset('js/angsuran/target-angsuran.js') }}"></script>
            <script src="{{ asset('js/pencairan/nominal.js') }}"></script>
            <script src="{{ asset('js/all/reset-form.js') }}"></script>
            <script src="{{ asset('js/all/geolokasi.js') }}"></script>
            <script src="{{ asset('js/angsuran/search/tambah.js') }}"></script>
        </x-slot>
    </x-bar.navbar>
@endsection
