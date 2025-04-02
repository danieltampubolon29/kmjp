@extends('layouts.aplication')
@section('title', 'Angsuran')
@section('content')
    <x-bar.navbar>Halaman Angsuran
        <x-slot name="content">
            <div class="container mt-4">
                <div class="card shadow">
                    <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
                        <h5 class="mb-0">Daftar Angsuran</h5>
                        <div>
                            <a href="{{ route('angsuran.create') }}" class="btn btn-light"><i class="ri-user-add-line"></i></a>
                        </div>
                    </div>
                    <div class="card-body">
                        <form action="{{ route('angsuran.index') }}" method="GET" class="d-flex mb-3">
                            <div class="input-group">
                                <input type="text" name="search" class="form-control" placeholder="Cari Angsuran"
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
                                    @foreach ($angsurans as $angsuran)
                                        <tr class="align-middle" style="white-space: nowrap;" data-href="{{ route('angsuran.show', $angsuran->id) }}">
                                            <td>{{ $loop->iteration }}</td>
                                            <td>{{ $angsuran->pencairan->no_anggota }}</td>
                                            <td>{{ $angsuran->pencairan->nama }}</td>
                                            <td>Rp. {{ number_format($angsuran->nominal, 0, ',', '.') }},-</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        {{ $angsurans->appends(['search' => $search ?? ''])->links('pagination::bootstrap-5') }}
                    </div>
                </div>
            </div>
            <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
            <script src="{{ asset('js/all/show.js') }}"></script>
        </x-slot>
    </x-bar.navbar>
@endsection
