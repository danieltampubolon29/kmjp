@extends('layouts.aplication')
@section('title', 'Progres')
@section('content')
    <x-bar.navbar>Target Harian
        <x-slot name="content">
            <div class="container mt-4">
                <div class="card shadow">
                    <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
                        <h5 class="mb-0">Daftar Pencairan {{ $currentDay }}</h5>
                        <div>
                            <a href="{{ route('pencairan.create') }}" class="btn btn-light"><i
                                    class="ri-hand-coin-line"></i></i></a>
                        </div>
                    </div>
                    <div class="card-body">
                        <form action="{{ route('pencairan.index') }}" method="GET" class="d-flex mb-3">
                            <div class="input-group">
                                <input type="text" name="search" class="form-control" placeholder="Cari Pencairan"
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
                                        <th>TANGGAL</th>
                                        <th>NOMINAL</th>
                                        <th>STATUS</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($pencairans as $pencairan)
                                        <tr data-href="{{ route('angsuran.create') }}">
                                            <td>{{ $loop->iteration }}</td>
                                            <td>{{ $pencairan->anggota->no_anggota }}</td>
                                            <td>{{ $pencairan->anggota->nama }}</td>
                                            <td>{{ $pencairan->tanggal_pencairan }}</td>
                                            <td>Rp. {{ number_format($pencairan->nominal, 0, ',', '.') }},-</td>
                                            <td>
                                                @if ($pencairan->status == 1)
                                                    <button class="btn btn-success btn-sm text-light fw-bold">Lunas</button>
                                                @elseif($pencairan->status == 0)
                                                    <button
                                                        class="btn btn-warning btn-sm text-light fw-bold">Proses</button>
                                                @endif
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        {{ $pencairans->appends(['search' => $search ?? ''])->links('pagination::bootstrap-5') }}
                    </div>
                </div>
            </div>
            <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
            <script src="{{ asset('js/all/show.js') }}"></script>
        </x-slot>
    </x-bar.navbar>
@endsection
