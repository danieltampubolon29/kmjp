@extends('layouts.aplication')
@section('title', 'Pencairan')
@section('content')
    <x-bar.navbar>Halaman Pencairan
        <x-slot name="content">
            <div class="container mt-4">
                <x-alert-message></x-alert-message>
                <div class="card shadow">
                    <form class="form-submit" id="resetForm" method="POST" action="{{ route('pencairan.store') }}">
                        @csrf
                        <div class="card-header bg-primary text-white">
                            <h5 class="mb-0">Form Tambah Pencairan</h5>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-6 mb-3 position-relative">
                                    <label for="searchInput" class="form-label">Cari Anggota</label>
                                    <input type="text" id="searchInput" class="form-control"
                                        placeholder="Ketik nama atau no anggota">
                                    <div id="searchResults" class="position-absolute w-100 overflow-auto"
                                        style="max-height: 200px; display: none; z-index: 1000;"></div>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label for="no_anggota" class="form-label">No Anggota</label>
                                    <input type="text" name="no_anggota" id="no_anggota" class="form-control" required readonly>
                                </div>
                            </div>
                            <input type="hidden" name="anggota_id" id="anggota_id">

                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="nama" class="form-label">Nama Anggota</label>
                                    <input type="text" name="nama" id="nama" class="form-control" required readonly>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label for="pinjaman_ke" class="form-label">Pinjaman Ke</label>
                                    <input type="number" id="pinjaman_ke" name="pinjaman_ke" class="form-control" readonly>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="produk" class="form-label">Produk</label>
                                    <select id="produk" name="produk" class="form-control" required>
                                        <option value="Harian">Harian</option>
                                        <option value="Mingguan">Mingguan</option>
                                    </select>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label for="nominal" class="form-label">Nominal Pinjaman</label>
                                    <input type="text" id="nominal" name="nominal" class="form-control" required>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="tenor" class="form-label">Tenor</label>
                                    <input type="number" id="tenor" name="tenor" class="form-control" required>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label for="jatuh_tempo" class="form-label">Jatuh Tempo</label>
                                    <select id="jatuh_tempo" name="jatuh_tempo" class="form-control" required>
                                        <option value="Senin">Senin</option>
                                        <option value="Selasa">Selasa</option>
                                        <option value="Rabu">Rabu</option>
                                        <option value="Kamis">Kamis</option>
                                        <option value="Jumat">Jumat</option>
                                        <option value="Harian">Harian</option>
                                    </select>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="tanggal_pencairan" class="form-label">Tanggal Pencairan</label>
                                    <input type="date" id="tanggal_pencairan" name="tanggal_pencairan"
                                        class="form-control" value="{{ date('Y-m-d') }}" required>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label for="marketing" class="form-label">Marketing</label>
                                    <select id="marketing" name="marketing" class="form-control" required>
                                        <option value="Hitler">Hitler</option>
                                        <option value="Jubrito">Jubrito</option>
                                        <option value="Hendri">Hendri</option>
                                    </select>
                                </div>
                            </div>
                            <input type="hidden" name="marketing_id" id="marketing_id" value="{{ auth()->id() }}">
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
            <script src="{{ asset('js/pencairan/nominal.js') }}"></script>
            <script src="{{ asset('js/pencairan/produk.js') }}"></script>
            <script src="{{ asset('js/all/reset-form.js') }}"></script>
            <script src="{{ asset('js/all/geolokasi.js') }}"></script>
            <script src="{{ asset('js/pencairan/search/tambah.js') }}"></script>
        </x-slot>
    </x-bar.navbar>
@endsection
