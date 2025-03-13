@extends('layouts.aplication')
@section('title', 'Simpanan')
@section('content')
    <x-bar.navbar>Halaman Simpanan
        <x-slot name="content">
            <div class="container mt-4">
                <x-alert-message></x-alert-message>
                <div class="card shadow">
                    <form class="form-submit" id="resetForm" method="POST" action="{{ route('simpanan.update', $simpanan->id) }}">
                        @csrf
                        @method('PUT')
                        <div class="card-header bg-primary text-white">
                            <h5 class="mb-0">Form Edit Simpanan</h5>
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
                                    <label for="tanggal_transaksi" class="form-label">Tanggal Transaksi</label>
                                    <input type="date" value="{{ $simpanan->tanggal_transaksi }}" class="form-control" id="tanggal_transaksi"
                                        name="tanggal_transaksi" required>
                                </div>
                            </div>
                            <input type="hidden" value="{{ $simpanan->anggota_id }}" name="anggota_id" id="anggota_id">

                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="no_anggota" class="form-label">No Anggota</label>
                                    <input type="text" value="{{ $simpanan->anggota->no_anggota }}" id="no_anggota" class="form-control" readonly>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label for="nama_anggota" class="form-label">Nama Anggota</label>
                                    <input type="text" value="{{ $simpanan->anggota->nama }}" id="nama_anggota" class="form-control" readonly>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="jenis_transaksi" class="form-label">Jenis Transaksi</label>
                                    <select class="form-control" id="jenis_transaksi" name="jenis_transaksi" required>
                                        <option value="Setor" {{ $simpanan->jenis_transaksi === 'Setor' ? 'selected' : '' }}>Setor</option>
                                        <option value="Tarik" {{ $simpanan->jenis_transaksi === 'Tarik' ? 'selected' : '' }}>Tarik</option>
                                    </select>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label for="jenis_simpanan" class="form-label">Jenis Simpanan</label>
                                    <select class="form-control" id="jenis_simpanan" name="jenis_simpanan" required>
                                        <option value="Pokok" {{ $simpanan->jenis_simpanan === 'Pokok' ? 'selected' : '' }}>Pokok</option>
                                        <option value="Simpanan" {{ $simpanan->jenis_simpanan === 'Simpanan' ? 'selected' : '' }}>Simpanan</option>
                                        <option value="Sukarela" {{ $simpanan->jenis_simpanan === 'Sukarela' ? 'selected' : '' }}>Sukarela</option>
                                        <option value="Deposito" {{ $simpanan->jenis_simpanan === 'Deposito' ? 'selected' : '' }}>Deposito</option>
                                    </select>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="nominal" class="form-label">Nominal Simpanan</label>
                                    <input type="text" value="{{ number_format($simpanan->nominal, 0, ',', '.') }}" class="form-control" id="nominal" name="nominal" required>
                                </div>
                            </div>
                        </div>

                        <div class="card-footer bg-light d-flex justify-content-end">
                            <a href="{{ route('simpanan.show', $simpanan->id) }}" class="btn btn-secondary me-2">
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
            <script src="{{ asset('js/all/reset-form.js') }}"></script>
            <script src="{{ asset('js/simpanan/search/tambah.js') }}"></script>
        </x-slot>
    </x-bar.navbar>
@endsection
