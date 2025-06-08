@extends('layouts.aplication')
@section('title', 'Anggota')
@section('content')
    <x-bar.navbar>Halaman Anggota
        <x-slot name="content">
            <div class="container mt-4">
                <div class="card shadow">
                    <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
                        <h5 class="mb-0">Daftar Anggota</h5>
                        <div>
                            <a href="{{ route('anggota.create') }}" class="btn btn-light"><i class="ri-user-add-line"></i></a>
                        </div>
                    </div>
                    <div class="card-body">
                        <form action="{{ route('anggota.index') }}" method="GET" class="d-flex mb-3">
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
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($anggotas as $anggota)
                                        <tr class="align-middle" style="white-space: nowrap;"
                                            data-href="{{ route('anggota.show', $anggota->id) }}">
                                            <td>{{ $loop->iteration }}</td>
                                            <td>{{ $anggota->no_anggota }}</td>
                                            <td>{{ $anggota->nama }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        {{ $anggotas->appends(['search' => $search ?? ''])->links('pagination::bootstrap-5') }}
                    </div>
                </div>
            </div>
            <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
            <script src="{{ asset('js/all/show.js') }}"></script>
        </x-slot>
    </x-bar.navbar>
@endsection
