@extends('layouts.aplication')
@section('title', 'Anggota')
@section('content')
    <x-bar.navbar>Halaman Anggota
        <x-slot name="content">
            <div class="container mt-4">
                <x-alert-message></x-alert-message>
                <div class="card shadow">
                    <form class="form-submit" method="POST" action="{{ route('anggota.update', $anggota->id) }}">
                        <div class="card-header bg-primary text-white">
                            <h5 class="mb-0">Form Edit Anggota</h5>
                        </div>
                        <div class="card-body">
                            @csrf
                            @method('PUT')
                            <div class="row">
                                <div class="col-md-6">
                                    <label for="no_anggota" class="form-label">No Anggota</label>
                                    <input type="text" name="no_anggota" id="no_anggota" class="form-control"
                                        value="{{ $anggota->no_anggota }}" readonly>
                                </div>
                                <div class="col-md-6">
                                    <label for="nama" class="form-label">Nama</label>
                                    <input type="text" value="{{ $anggota->nama }}" class="form-control" id="nama"
                                        name="nama" required>
                                </div>
                            </div>
                            <div class="row mt-3">
                                <div class="col-md-6">
                                    <label for="tanggal_lahir" class="form-label">Tanggal Lahir</label>
                                    <input type="date" value="{{ $anggota->tanggal_lahir }}" class="form-control"
                                        id="tanggal_lahir" name="tanggal_lahir" required>
                                </div>
                                <div class="col-md-6">
                                    <label for="no_hp" class="form-label">Nomor Handphone</label>
                                    <input type="text" value="{{ $anggota->no_hp }}" class="form-control" id="no_hp"
                                        name="no_hp">
                                </div>
                            </div>
                            <div class="row mt-3">
                                <div class="col-md-12">
                                    <label for="alamat_ktp" class="form-label">Alamat KTP</label>
                                    <textarea class="form-control" id="alamat_ktp" name="alamat_ktp" rows="2" required> {{ $anggota->alamat_ktp }}</textarea>
                                </div>
                            </div>
                            <div class="row mt-3">
                                <div class="col-md-12">
                                    <label for="alamat_domisili" class="form-label">Alamat Domisili</label>
                                    <textarea class="form-control" id="alamat_domisili" name="alamat_domisili" rows="2" required>{{ $anggota->alamat_domisili }}</textarea>
                                </div>
                            </div>
                            <div class="row mt-3">
                                <div class="col-md-6">
                                    <label for="tanggal_daftar" class="form-label">Tanggal Daftar</label>
                                    <input type="date" value="{{ $anggota->tanggal_daftar }}" class="form-control"
                                        id="tanggal_daftar" name="tanggal_daftar" required>
                                </div>
                            </div>
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
            <script src="{{ asset('js/all/reset-form.js') }}"></script>
        </x-slot>
    </x-bar.navbar>
@endsection
