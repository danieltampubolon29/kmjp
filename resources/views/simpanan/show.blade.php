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
                                <h5 class="mb-0">Informasi Pencairan</h5>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-6">
                                        <p class="mb-1"><strong>No Anggota</strong></p>
                                        <p>{{ $pencairan->anggota->no_anggota }}</p>
                                    </div>
                                    <div class="col-md-6">
                                        <p class="mb-1"><strong>Nama</strong></p>
                                        <p>{{ $pencairan->anggota->nama }}</p>
                                    </div>
                                </div>

                                <div class="row mt-3">
                                    <div class="col-md-6">
                                        <p class="mb-1"><strong>Pinjaman Ke</strong></p>
                                        <p>{{ $pencairan->pinjaman_ke }}</p>
                                    </div>
                                    <div class="col-md-6">
                                        <p class="mb-1"><strong>Produk</strong></p>
                                        <p>{{ $pencairan->produk }}</p>
                                    </div>
                                </div>

                                <div class="row mt-3">
                                    <div class="col-md-6">
                                        <p class="mb-1"><strong>Nominal</strong></p>
                                        <p>Rp. {{ number_format($pencairan->nominal, 0, ',', '.') }},-</p>
                                    </div>
                                    <div class="col-md-6">
                                        <p class="mb-1"><strong>Tenor</strong></p>
                                        <p>{{ $pencairan->tenor }}</p>
                                    </div>
                                </div>
                                <div class="row mt-3">
                                    <div class="col-md-6">
                                        <p class="mb-1"><strong>Jatuh Tempo</strong></p>
                                        <p>{{ $pencairan->jatuh_tempo }}</p>
                                    </div>
                                    <div class="col-md-6">
                                        <p class="mb-1"><strong>Tanggal Pencairan</strong></p>
                                        <p>{{ \Carbon\Carbon::parse($pencairan->tanggal_pencairan)->translatedFormat('d F Y') }}
                                        </p>
                                    </div>
                                </div>
                                <div class="row mt-3">
                                    <div class="col-md-6">
                                        <p class="mb-1"><strong>Marketing</strong></p>
                                        <p>{{ $pencairan->marketing }}</p>
                                    </div>
                                </div>

                                <div class="mt-4">
                                    <a href="{{ route('pencairan.index') }}" class="btn btn-secondary"><i
                                            class="ri-home-4-line"></i></a>
                                    <a class="btn btn-success"
                                        href="https://www.google.com/maps?q={{ $pencairan->latitude }},{{ $pencairan->longitude }}"
                                        target="_blank">
                                        <i class="ri-map-pin-user-line"></i>
                                    </a>

                                    @if (auth()->check() && auth()->user()->role === 'admin')
                                        <a href="{{ route('pencairan.edit', $pencairan->id) }}" class="btn btn-warning "><i
                                                class="ri-pencil-fill"></i></a>
                                        <button type="button" class="btn btn-danger" data-bs-toggle="modal"
                                            data-bs-target="#deleteModal{{ $pencairan->id }}">
                                            <i class="ri-delete-bin-2-fill"></i>
                                        </button>
                                        <button type="button" class="btn btn-primary" data-bs-toggle="modal"
                                            data-bs-target="#uploadModal{{ $pencairan->id }}">
                                            <i class="ri-image-line"></i>
                                        </button>
                                        <button type="button" class="btn btn-dark" data-bs-toggle="modal"
                                            data-bs-target="#lockConfirmationModal{{ $pencairan->id }}">
                                            @if ($pencairan->is_locked)
                                                <i class="ri-lock-unlock-line"></i>
                                            @else
                                                <i class="ri-lock-2-fill"></i>
                                            @endif
                                        </button>
                                    @elseif (auth()->check() && auth()->user()->role === 'marketing')
                                        @if (!$pencairan->is_locked)
                                            <a href="{{ route('pencairan.edit', $pencairan->id) }}" class="btn btn-warning "><i
                                                    class="ri-pencil-fill"></i></a>
                                            <button type="button" class="btn btn-danger" data-bs-toggle="modal"
                                                data-bs-target="#deleteModal{{ $pencairan->id }}">
                                                <i class="ri-delete-bin-2-fill"></i>
                                            </button>
                                            <button type="button" class="btn btn-primary" data-bs-toggle="modal"
                                                data-bs-target="#uploadModal{{ $pencairan->id }}">
                                                <i class="ri-image-line"></i>
                                            </button>
                                            <button type="button" class="btn btn-dark" data-bs-toggle="modal"
                                                data-bs-target="#lockConfirmationModal{{ $pencairan->id }}"><i
                                                    class="ri-lock-2-fill"></i></button>
                                        @endif
                                    @endif
                                </div>
                                @include('pencairan.modal.kunci', ['pencairan' => $pencairan])
                                @include('pencairan.modal.hapus', ['pencairan' => $pencairan])
                                @include('pencairan.modal.upload', ['pencairan' => $pencairan]) 
                            </div>
                        </div>
                    </div>

                    <!-- Image Card -->
                    <div class="col-md-3 mb-5">
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
                    </div>

                </div>
            </div>
        </x-slot>
    </x-bar.navbar>
@endsection
