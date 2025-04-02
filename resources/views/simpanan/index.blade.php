@extends('layouts.aplication')
@section('title', 'Simpanan')
@section('content')
    <x-bar.navbar>Halaman Simpanan
        <x-slot name="content">
            <div class="container mt-4">
                <div class="card shadow">
                    <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
                        <h5 class="mb-0">Daftar Simpanan</h5>
                        <div>
                            <a href="{{ route('simpanan.create') }}" class="btn btn-light"><i class="ri-wallet-line"></i></a>
                        </div>
                    </div>
                    <div class="card-body">
                        <form action="{{ route('simpanan.index') }}" method="GET" class="d-flex mb-3">
                            <div class="input-group">
                                <input type="text" name="search" class="form-control" placeholder="Cari Anggota"
                                    value="{{ $search ?? '' }}">
                                <button type="submit" class="btn btn-primary">Cari</button>
                            </div>
                        </form>
                        <x-alert-message></x-alert-message>

                        <div class="table-responsive">
                            <table class="table table-light table-striped table-hover text-center">
                                <thead>
                                    <tr class="table-dark text-center align-middle">
                                        <th>NO</th>
                                        <th>NO ANGGOTA</th>
                                        <th>NAMA</th>
                                        <th>NOMINAL</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($simpanans as $simpanan)
                                        <tr class="align-middle" style="white-space: nowrap;" data-href="{{ route('simpanan.show', $simpanan->id) }}">
                                            <td>{{ $loop->iteration }}</td>
                                            <td>{{ $simpanan->anggota->no_anggota }}</td>
                                            <td>{{ $simpanan->anggota->nama }}</td>
                                            <td>Rp. {{ number_format($simpanan->nominal, 0, ',', '.') }},-</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>

                            {{ $simpanans->appends(['search' => $search ?? ''])->links('pagination::bootstrap-5') }}
                    </div>
                </div>
            </div>
            <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
            <script src="{{ asset('js/all/show.js') }}"></script>
        </x-slot>
    </x-bar.navbar>
@endsection