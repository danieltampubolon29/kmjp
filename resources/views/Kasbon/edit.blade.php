@extends('layouts.aplication')
@section('title', 'Kasbon')
@section('content')
    <x-bar.navbar>Edit Kasbon
        <x-slot name="content">
            <div class="container mt-4">
                <x-alert-message></x-alert-message>
                <div class="card shadow">
                    <form class="form-submit" id="resetForm" method="POST" action="{{ route('kasbon.update', $kasbon->id) }}">
                        @csrf
                        @method('PUT')
                        <div class="card-header bg-primary text-white">
                            <h5 class="mb-0">Form Edit Kasbon</h5>
                        </div>
                        <div class="card-body">
                            <div class="mb-3">
                                <label for="marketing_id" class="form-label">Pilih Marketing:</label>
                                <select class="form-select" id="marketing_id" name="marketing_id" required>
                                    @foreach ($users as $user)
                                        <option value="{{ $user->id }}"
                                            {{ $user->id == $kasbon->marketing_id ? 'selected' : '' }}>
                                            {{ $user->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="mb-3">
                                <label for="tanggal" class="form-label">Tanggal:</label>
                                <input type="date" value="{{ old('tanggal', $kasbon->tanggal) }}" class="form-control"
                                    id="tanggal" name="tanggal" required>
                            </div>
                            <div class="mb-3">
                                <label for="nominal" class="form-label">Nominal:</label>
                                <input type="text" class="form-control" id="nominal" name="nominal"
                                    value="{{ number_format($kasbon->nominal, 0, ',', '.') }}"
                                    placeholder="Masukkan Nominal" oninput="formatNominal(this)" required>
                            </div>
                        </div>

                        <div class="card-footer bg-light d-flex justify-content-end">
                            <a href="{{ route('kasbon.show',$kasbon->id) }}" class="btn btn-secondary me-2">
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
            <script src="{{ asset('js/laporan/nominal-reset.js') }}"></script>
        </x-slot>
    </x-bar.navbar>
@endsection
