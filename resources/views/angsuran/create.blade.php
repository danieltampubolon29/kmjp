@extends('layouts.aplication')
@section('title', 'Tambah Anggota')
@section('content')
    <x-bar.navbar>Tambah Anggota
        <x-slot name="content">
            <div class="container mt-4">
                <x-alert-message></x-alert-message>
                <div class="card shadow">
                    <form class="form-submit" id="resetForm" method="POST" action="{{ route('anggota.store') }}">
                        <div class="card-header bg-primary text-white">
                            <h5 class="mb-0">Form Tambah Anggota</h5>
                        </div>
                        <div class="card-body">
                            @csrf
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="no_anggota" class="form-label">No Anggota</label>
                                    <input type="text" name="no_anggota" id="no_anggota" class="form-control"
                                        value="{{ $nextNoAnggota }}" required readonly>
                                </div>

                                <div class="col-md-6 mb-3">
                                    <label for="nama" class="form-label">Nama</label>
                                    <input type="text" class="form-control" id="nama" name="nama"
                                        value="{{ old('nama') }}" required>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="tanggal_lahir" class="form-label">Tanggal Lahir</label>
                                    <input type="date" class="form-control" id="tanggal_lahir" name="tanggal_lahir"
                                        value="{{ old('tanggal_lahir') }} "required>
                                </div>

                                <div class="col-md-6 mb-3">
                                    <label for="no_hp" class="form-label">Nomor Handphone</label>
                                    <input type="text" class="form-control" id="no_hp" name="no_hp"
                                        value="{{ old('no_hp') }}">
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-12 mb-3">
                                    <label for="alamat_ktp" class="form-label">Alamat KTP</label>
                                    <textarea class="form-control" id="alamat_ktp" name="alamat_ktp" rows="2" required>{{ old('alamat_ktp') }}</textarea>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-12 mb-3">
                                    <label for="alamat_domisili" class="form-label">Alamat Domisili</label>
                                    <textarea class="form-control" id="alamat_domisili" name="alamat_domisili" rows="2" required>{{ old('alamat_domisili') }}</textarea>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="tanggal_daftar" class="form-label">Tanggal Daftar</label>
                                    <input type="date" class="form-control" id="tanggal_daftar" name="tanggal_daftar"
                                        value="{{ old('tanggal_daftar') }}" required>
                                </div>
                            </div>

                            <input type="hidden" id="latitude" name="latitude" value="{{ old('latitude') }}" required
                                readonly>
                            <input type="hidden" id="longitude" name="longitude" value="{{ old('longitude') }}" required
                                readonly>
                        </div>

                        <div class="card-footer bg-light d-flex justify-content-end">
                            <a href="{{ route('anggota.index') }}" class="btn btn-secondary me-2">
                                <i class="ri-home-4-line"></i>
                            </a>
                            <button type="button" class="btn btn-danger me-2" onclick="resetForm()">
                                <i class="ri-refresh-line"></i>
                            </button>
                            <button type="submit" class="btn btn-success">
                                <i class="ri-save-line"></i>
                            </button>
                    </form>
                </div>
            </div>
            </div>
            <script src="{{ asset('js/all/geolokasi.js') }}"></script>
            <script src="{{ asset('js/all/reset-form.js') }}"></script>
        </x-slot>
    </x-bar.navbar>
@endsection
