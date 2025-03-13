@extends('layouts.aplication')
@section('title', 'Simpanan')
@section('content')
    <x-bar.navbar>Halaman Simpanan
        <x-slot name="content">
            <div class="container mt-4">
                <div class="row">
                    <!-- Main Card -->
                    <div class="col-md-9 mb-3">
                        <x-alert-message></x-alert-message>
                        <div class="card shadow">
                            <div class="card-header bg-primary text-white">
                                <h5 class="mb-0">Informasi Simpanan</h5>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-6">
                                        <p class="mb-1"><strong>No Anggota</strong></p>
                                        <p>{{ $simpanan->anggota->no_anggota }}</p>
                                    </div>
                                    <div class="col-md-6">
                                        <p class="mb-1"><strong>Nama</strong></p>
                                        <p>{{ $simpanan->anggota->nama }}</p>
                                    </div>
                                </div>

                                <div class="row mt-3">
                                    <div class="col-md-6">
                                        <p class="mb-1"><strong>Tanggal Transaksi</strong></p>
                                        <p>{{ \Carbon\Carbon::parse($simpanan->tanggal_pencairan)->translatedFormat('d F Y') }}
                                    </div>
                                    <div class="col-md-6">
                                        <p class="mb-1"><strong>Jenis Transaksi</strong></p>
                                        <p>{{ $simpanan->jenis_transaksi }}</p>
                                    </div>
                                </div>

                                <div class="row mt-3">
                                    <div class="col-md-6">
                                        <p class="mb-1"><strong>Jenis Simpanan</strong></p>
                                        <p>{{ $simpanan->jenis_simpanan }}</p>
                                    </div>
                                    <div class="col-md-6">
                                        <p class="mb-1"><strong>Nominal</strong></p>
                                        <p>Rp. {{ number_format($simpanan->nominal, 0, ',', '.') }},-</p>
                                    </div>
                                </div>

                                <div class="mt-4">
                                    <a href="{{ route('simpanan.index') }}" class="btn btn-secondary"><i
                                            class="ri-home-4-line"></i></a>

                                    @if (auth()->check() && auth()->user()->role === 'admin')
                                        <a href="{{ route('pencairan.edit', $simpanan->id) }}" class="btn btn-warning "><i
                                                class="ri-pencil-fill"></i></a>
                                        <button type="button" class="btn btn-danger" data-bs-toggle="modal"
                                            data-bs-target="#deleteModal{{ $simpanan->id }}">
                                            <i class="ri-delete-bin-2-fill"></i>
                                        </button>
                                        <button type="button" class="btn btn-dark" data-bs-toggle="modal"
                                            data-bs-target="#lockConfirmationModal{{ $simpanan->id }}">
                                            @if ($simpanan->is_locked)
                                                <i class="ri-lock-unlock-line"></i>
                                            @else
                                                <i class="ri-lock-2-fill"></i>
                                            @endif
                                        </button>
                                    @elseif (auth()->check() && auth()->user()->role === 'marketing')
                                        @if (!$simpanan->is_locked)
                                            <a href="{{ route('simpanan.edit', $simpanan->id) }}"
                                                class="btn btn-warning "><i class="ri-pencil-fill"></i></a>
                                            <button type="button" class="btn btn-danger" data-bs-toggle="modal"
                                                data-bs-target="#deleteModal{{ $simpanan->id }}">
                                                <i class="ri-delete-bin-2-fill"></i>
                                            </button>
                                            <button type="button" class="btn btn-dark" data-bs-toggle="modal"
                                                data-bs-target="#lockConfirmationModal{{ $simpanan->id }}"><i
                                                    class="ri-lock-2-fill"></i></button>
                                        @endif
                                    @endif
                                </div>
                                @include('simpanan.modal.kunci', ['simpanan' => $simpanan])
                                @include('simpanan.modal.hapus', ['simpanan' => $simpanan])
                            </div>
                        </div>
                    </div>

                    <!-- Image Card -->
                    {{-- <div class="col-md-3 mb-5">
                        <div class="card shadow">
                            <div class="card-header bg-primary d-flex justify-content-between align-items-center">
                                <h5 class="mb-0 text-white">Foto Pencairan</h5>
                                @if ($pencairan->foto_pencairan)
                                    <a href="{{ asset('storage/' . $pencairan->foto_pencairan) }}" download
                                        class="btn btn-sm btn-light">
                                        <i class="ri-download-2-line"></i>
                                    </a>
                                @endif
                            </div>
                            <div class="card-body text-center">
                                @if ($pencairan->foto_pencairan)
                                    <img src="{{ asset('storage/' . $pencairan->foto_pencairan) }}" class="img-fluid mb-3"
                                        style="max-width: 100%; height: auto; margin-bottom: 1rem;" alt="Foto Pencairan">
                                @else
                                    <p class="text-muted">Tidak ada foto Pencairan.</p>
                                @endif
                            </div>
                        </div>

                        <div class="card shadow mt-3">
                            <div class="card-header bg-primary d-flex justify-content-between align-items-center">
                                <h5 class="mb-0 text-white">Foto Rumah</h5>
                                @if ($pencairan->foto_rumah)
                                    <a href="{{ asset('storage/' . $pencairan->foto_rumah) }}" download
                                        class="btn btn-sm btn-light">
                                        <i class="ri-download-2-line"></i>
                                    </a>
                                @endif
                            </div>
                            <div class="card-body text-center">
                                @if ($pencairan->foto_rumah)
                                    <img src="{{ asset('storage/' . $pencairan->foto_rumah) }}" class="img-fluid mb-3"
                                        style="max-width: 100%; height: auto; margin-bottom: 1rem;" alt="Foto Rumah">
                                @else
                                    <p class="text-muted">Tidak ada foto Rumah.</p>
                                @endif
                            </div>
                        </div>
                    </div> --}}

                </div>
            </div>
        </x-slot>
    </x-bar.navbar>
@endsection
