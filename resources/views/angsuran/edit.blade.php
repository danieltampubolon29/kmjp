@extends('layouts.aplication')
@section('title', 'Edit Angsuran')
@section('content')
    <x-bar.navbar>Halaman Edit Angsuran
        <x-slot name="content">
            <div class="container mt-4">
                <x-alert-message></x-alert-message>
                <div class="card shadow">
                    <form class="form-submit" id="resetForm" method="POST"
                        action="{{ route('angsuran.update', $angsuran->id) }}">
                        @csrf
                        @method('PUT')
                        <div class="card-header bg-primary text-white">
                            <h5 class="mb-0">Form Edit Angsuran</h5>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-6 mb-3 position-relative">
                                    <label for="searchInput" class="form-label">Cari Pencairan</label>
                                    <input type="text" id="searchInput" class="form-control"
                                        placeholder="Ketik nama atau no anggota"
                                        value="{{ $angsuran->pencairan->no_anggota . ' - ' . $angsuran->pencairan->nama }}">
                                    <div id="searchResults" class="position-absolute w-100 overflow-auto"
                                        style="max-height: 200px; display: none; z-index: 1000;"></div>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label for="jenis_transaksi" class="form-label">Jenis Transaksi</label>
                                    <select id="jenis_transaksi" name="jenis_transaksi" class="form-control" required>
                                        <option value="001 - Angsuran"
                                            {{ $angsuran->jenis_transaksi == '001 - Angsuran' ? 'selected' : '' }}>001 -
                                            Angsuran</option>
                                        <option value="009 - Pemutihan"
                                            {{ $angsuran->jenis_transaksi == '009 - Pemutihan' ? 'selected' : '' }}>009 -
                                            Pemutihan</option>
                                    </select>
                                </div>
                            </div>
                            <input type="text" name="pencairan_id" id="pencairan_id"
                                value="{{ $angsuran->pencairan_id }}">
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="angsuran_ke" class="form-label">Angsuran Ke</label>
                                    <input type="text" id="angsuran_ke" name="angsuran_ke" class="form-control"
                                        value="{{ $angsuran->angsuran_ke }}" readonly>

                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="nominal" class="form-label">Nominal Angsuran</label>

                                    <input type="text" id="nominal" name="nominal" class="form-control"
                                        value="{{ $angsuran->nominal }}" required>

                                </div>
                                <div class="col-md-6 mb-3">
                                    <label for="tanggal_angsuran" class="form-label">Tanggal Angsuran</label>
                                    <input type="date" id="tanggal_angsuran" name="tanggal_angsuran" class="form-control"
                                        value="{{ $angsuran->tanggal_angsuran }}" required>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="no_anggota" class="form-label">No Anggota</label>
                                    <input type="text" id="no_anggota" class="form-control"
                                        value="{{ $angsuran->pencairan->no_anggota }}" readonly>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label for="nama" class="form-label">Nama Anggota</label>
                                    <input type="text" id="nama" class="form-control"
                                        value="{{ $angsuran->pencairan->nama }}" readonly>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="pinjaman_ke" class="form-label">Pinjaman Ke</label>
                                    <input type="text" id="pinjaman_ke" class="form-control"
                                        value="{{ $angsuran->pencairan->pinjaman_ke }}" readonly>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label for="produk" class="form-label">Produk</label>
                                    <input type="text" id="produk" class="form-control"
                                        value="{{ $angsuran->pencairan->produk }}" readonly>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="tenor" class="form-label">Tenor</label>
                                    <input type="text" id="tenor" class="form-control"
                                        value="{{ $angsuran->pencairan->tenor }}" readonly>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label for="sisa_kredit" class="form-label">Sisa Kredit</label>
                                    <input type="text" id="sisa_kredit" class="text-danger form-control"
                                        value="{{ $angsuran->pencairan->sisa_kredit }}" readonly>
                                </div>
                            </div>
                        </div>
                        <div class="card-footer bg-light d-flex justify-content-end">
                            <a href="{{ route('angsuran.index') }}" class="btn btn-secondary me-2">
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
            <script src="{{ asset('js/all/geolokasi.js') }}"></script>
            <script src="{{ asset('js/angsuran/search/edit.js') }}"></script>
        </x-slot>
    </x-bar.navbar>
@endsection
