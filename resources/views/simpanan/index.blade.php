@extends('layouts.aplication')
@section('title', 'Simpanan')
@section('content')
    <x-bar.navbar>Halaman Simpanan
        <x-slot name="content">
            <div class="container mt-4">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <a href="{{ route('simpanan.create') }}" class="btn btn-primary"><i class="ri-user-add-line"></i></a>
                    <form action="{{ route('simpanan.index') }}" method="GET" class="d-flex">
                        <div class="input-group">
                            <input type="text" name="search" class="form-control" placeholder="Cari Anggota"
                                value="{{ $search ?? '' }}">
                            <button type="submit" class="btn btn-primary">Cari</button>
                        </div>
                    </form>
                </div>
                <x-alert-message></x-alert-message>
                <div class="table-responsive">
                    <table class="table table-light table-striped table-hover text-center">
                        <thead>
                            <tr>
                                <th class="table-dark text-center">NO</th>
                                <th class="table-dark text-center">NO ANGGOTA</th>
                                <th class="table-dark text-center">NAMA</th>
                                <th class="table-dark text-center">NOMINAL</th>
                                <th class="table-dark text-center">AKSI</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($simpanans as $simpanan)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td>{{ $simpanan->anggota->no_anggota }}</td>
                                    <td>{{ $simpanan->anggota->nama }}</td>
                                    <td>Rp. {{ number_format($simpanan->nominal, 0, ',', '.') }},-</td>
                                    <td>
                                        <a href="{{route('anggota.show', $simpanan->id)}}" class="btn btn-success">Show</a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                    {{-- {{ $simpanans->appends(['search' => $search ?? ''])->links('pagination::bootstrap-5') }} --}}
                </div>
            </div>
            <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

            </div>
        </x-slot>
    </x-bar.navbar>
@endsection
