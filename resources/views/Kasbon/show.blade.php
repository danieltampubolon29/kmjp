    @extends('layouts.aplication')
    @section('title', 'Kasbon')
    @section('content')
        <x-bar.navbar>Halaman Kasbon
            <x-slot name="content">
                <div class="container mt-4">
                    <div class="row">
                        <div class="col-md-12 mb-3">
                            <x-alert-message></x-alert-message>
                            <div class="card shadow">
                                <div class="card-header bg-primary text-white">
                                    <h5 class="mb-0">Informasi Kasbon</h5>
                                </div>
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-md-6">
                                            <p class="mb-1"><strong>Marketing</strong></p>
                                            <p>{{ $kasbon->marketing->name }}</p>
                                        </div>
                                        <div class="col-md-6">
                                            <p class="mb-1"><strong>Tanggal</strong></p>
                                            <p>{{ $kasbon->tanggal }}</p>
                                        </div>
                                    </div>

                                    <div class="row mt-3">
                                        <div class="col-md-6">
                                            <p class="mb-1"><strong>Nominal</strong></p>
                                            <p>Rp. {{ number_format($kasbon->nominal, 0, ',', '.') }}</p>
                                        </div>
                                        <div class="col-md-6">
                                            <p class="mb-1"><strong>Sisa Kasbon</strong></p>
                                            <p
                                                class="
                                                @if ($kasbon->status == 1 && $kasbon->sisa_kasbon < 0) text-danger @endif ">
                                                Rp. {{ number_format($kasbon->sisa_kasbon, 0, ',', '.') }}
                                            </p>
                                        </div>

                                    </div>
                                    <div class="mt-4">
                                        <a href="{{ route('kasbon.index') }}" class="btn btn-secondary"><i
                                                class="ri-home-4-line"></i></a>
                                        @if (!$kasbon->is_locked)
                                            <a href="{{ route('kasbon.edit', $kasbon->id) }}" class="btn btn-warning "><i
                                                    class="ri-pencil-fill"></i></a>
                                            <button type="button" class="btn btn-danger" data-bs-toggle="modal"
                                                data-bs-target="#deleteModal{{ $kasbon->id }}">
                                                <i class="ri-delete-bin-2-fill"></i>
                                            </button>
                                            <button type="button" class="btn btn-primary" data-bs-toggle="modal"
                                                data-bs-target="#statusModal{{ $kasbon->id }}">
                                                <i class="ri-checkbox-circle-line"></i>
                                            </button>
                                        @endif
                                        <button type="button" class="btn btn-dark" data-bs-toggle="modal"
                                            data-bs-target="#lockConfirmationModal{{ $kasbon->id }}">
                                            @if ($kasbon->is_locked)
                                                <i class="ri-lock-unlock-line"></i>
                                            @else
                                                <i class="ri-lock-2-fill"></i>
                                            @endif
                                        </button>
                                    </div>
                                    @include('kasbon.modal.status', ['kasbon' => $kasbon])
                                    @include('kasbon.modal.hapus', ['kasbon' => $kasbon])
                                    @include('kasbon.modal.kunci', ['kasbon' => $kasbon])
                                </div>
                            </div>
                        </div>

                    </div>
                </div>
            </x-slot>
        </x-bar.navbar>
    @endsection
