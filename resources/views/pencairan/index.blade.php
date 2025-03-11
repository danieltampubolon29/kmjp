@extends('layouts.aplication')
@section('title', 'Pencairan')
@section('content')
    <x-bar.navbar>Halaman Pencairan
        <x-slot name="content">
            <div class="container mt-4">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <a href="{{ route('pencairan.create') }}" class="btn btn-primary"><i class="ri-user-add-line"></i></a>
                    <form action="{{ route('pencairan.index') }}" method="GET" class="d-flex">
                        <div class="input-group">
                            <input type="text" name="search" class="form-control" placeholder="Cari Pencairan"
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
                                <th class="table-dark text-center">TANGGAL</th>
                                <th class="table-dark text-center">NOMINAL</th>
                                <th class="table-dark text-center">AKSI</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($pencairans as $pencairan)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td>{{ $pencairan->anggota->no_anggota }}</td>
                                    <td>{{ $pencairan->anggota->nama }}</td>
                                    <td>{{ $pencairan->tanggal_pencairan }}</td>
                                    <td>Rp. {{ number_format($pencairan->nominal, 0, ',', '.') }},-</td>
                                    <td>
                                        <a href="{{route('pencairan.show', $pencairan->id)}}" class="btn btn-success">Show</a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                    {{ $pencairans->appends(['search' => $search ?? ''])->links('pagination::bootstrap-5') }}

                </div>
            </div>
            <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

            </div>
        </x-slot>
    </x-bar.navbar>
@endsection
