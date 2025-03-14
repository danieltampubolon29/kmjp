@extends('layouts.aplication')
@section('title', 'Angsuran')
@section('content')
    <x-bar.navbar>Halaman Angsuran
        <x-slot name="content">
            <div class="container mt-4">
                <div class="row">
                    <div class="col-md-9 mb-3">
                        <x-alert-message></x-alert-message>
                        <div class="card shadow">
                            <div class="card-header bg-primary text-white">
                                <h5 class="mb-0">Informasi Angsuran</h5>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-6">
                                        <p class="mb-1"><strong>No Anggota</strong></p>
                                        <p>{{ $angsuran->pencairan->no_anggota }}</p>
                                    </div>
                                    <div class="col-md-6">
                                        <p class="mb-1"><strong>Nama</strong></p>
                                        <p>{{ $angsuran->pencairan->nama }}</p>
                                    </div>
                                </div>

                                <div class="row mt-3">
                                    <div class="col-md-6">
                                        <p class="mb-1"><strong>Pinjaman Ke</strong></p>
                                        <p>{{ $angsuran->pencairan->pinjaman_ke }}</p>
                                    </div>
                                    <div class="col-md-6">
                                        <p class="mb-1"><strong>Jatuh Tempo</strong></p>
                                        <p>{{ $angsuran->jenis_transaksi }}</p>
                                    </div>
                                </div>

                                <div class="row mt-3">
                                    <div class="col-md-6">
                                        <p class="mb-1"><strong>Produk</strong></p>
                                        <p>{{ $angsuran->pencairan->produk }}</p>
                                    </div>
                                    <div class="col-md-6">
                                        <p class="mb-1"><strong>Nominal</strong></p>
                                        <p>Rp. {{ number_format($angsuran->nominal, 0, ',', '.') }},-</p>
                                    </div>
                                </div>
                                <div class="row mt-3">
                                    <div class="col-md-6">
                                        <p class="mb-1"><strong>Angsuran</strong></p>
                                        <p>{{$angsuran->angsuran_ke . " / " . $angsuran->pencairan->tenor }}</p>
                                    </div>
                                    <div class="col-md-6">
                                        <p class="mb-1"><strong>Jatuh Tempo</strong></p>
                                        <p>{{ $angsuran->pencairan->jatuh_tempo }}</p>
                                    </div>
                                </div>
                                <div class="row mt-3">
                                    <div class="col-md-6">
                                        <p class="mb-1"><strong>Tanggal Pencairan</strong></p>
                                        <p>{{ \Carbon\Carbon::parse($angsuran->tanggal_pencairan)->translatedFormat('d F Y') }}
                                        </p>
                                    </div>
                                    <div class="col-md-6">
                                        <p class="mb-1"><strong>Sisa Kredit</strong></p>
                                        <p>Rp. {{ number_format($angsuran->pencairan->sisa_kredit, 0, ',', '.') }},-</p>
                                    </div>
                                </div>

                                <div class="mt-4">
                                    <a href="{{ route('angsuran.index') }}" class="btn btn-secondary"><i
                                            class="ri-home-4-line"></i></a>
                                    <a class="btn btn-success"
                                        href="https://www.google.com/maps?q={{ $angsuran->latitude }},{{ $angsuran->longitude }}"
                                        target="_blank">
                                        <i class="ri-map-pin-user-line"></i>
                                    </a>

                                    @if (auth()->check() && auth()->user()->role === 'admin')
                                        <a href="{{ route('angsuran.edit', $angsuran->id) }}" class="btn btn-warning "><i
                                                class="ri-pencil-fill"></i></a>
                                        <button type="button" class="btn btn-danger" data-bs-toggle="modal"
                                            data-bs-target="#deleteModal{{ $angsuran->id }}">
                                            <i class="ri-delete-bin-2-fill"></i>
                                        </button>
                                        <button type="button" class="btn btn-dark" data-bs-toggle="modal"
                                            data-bs-target="#lockConfirmationModal{{ $angsuran->id }}">
                                            @if ($angsuran->is_locked)
                                                <i class="ri-lock-unlock-line"></i>
                                            @else
                                                <i class="ri-lock-2-fill"></i>
                                            @endif
                                        </button>
                                    @elseif (auth()->check() && auth()->user()->role === 'marketing')
                                        @if (!$angsuran->is_locked)
                                            <a href="{{ route('angsuran.edit', $angsuran->id) }}" class="btn btn-warning "><i
                                                    class="ri-pencil-fill"></i></a>
                                            <button type="button" class="btn btn-danger" data-bs-toggle="modal"
                                                data-bs-target="#deleteModal{{ $angsuran->id }}">
                                                <i class="ri-delete-bin-2-fill"></i>
                                            </button>
                                            <button type="button" class="btn btn-dark" data-bs-toggle="modal"
                                                data-bs-target="#lockConfirmationModal{{ $angsuran->id }}"><i
                                                    class="ri-lock-2-fill"></i></button>
                                        @endif
                                    @endif
                                </div>
                                 @include('angsuran.modal.kunci', ['angsuran' => $angsuran])
                                @include('angsuran.modal.hapus', ['angsuran' => $angsuran])  
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </x-slot>
    </x-bar.navbar>
@endsection
