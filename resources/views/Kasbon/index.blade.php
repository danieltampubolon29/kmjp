@extends('layouts.aplication')
@section('title', 'Kasbon')
@section('content')
    <x-bar.navbar>
        Kasbon Harian Marketing
        <x-slot name="content">
            <div class="container mt-4">
                <div class="card shadow">
                    <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
                        <h5 class="mb-0">Daftar Kasbon</h5>
                        <div>
                            <a href="{{ route('kasbon.create') }}" class="btn btn-light"><i class="ri-user-add-line"></i></a>
                        </div>
                    </div>
                    <div class="card-body">
                        <form action="{{ route('kasbon.index') }}" method="GET" class="d-flex mb-3">
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
                                        <th>TANGGAL</th>
                                        <th>MARKETING</th>
                                        <th>NOMINAL</th>
                                        <th>SISA KASBON</th>
                                        <th>STATUS</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($kasbons as $kasbon)
                                        <tr data-href="{{ route('kasbon.show', $kasbon->id) }}">
                                            <td>{{ $loop->iteration }}</td>
                                            <td>{{ \Carbon\Carbon::parse($kasbon->tanggal)->translatedFormat('d F Y') }}
                                            </td>
                                            <td>{{ $kasbon->marketing->name }}</td>
                                            <td>Rp. {{ number_format($kasbon->nominal, 0, ',', '.') }}</td>
                                            <td
                                                class="
                                            @if ($kasbon->status == 1 && $kasbon->sisa_kasbon < 0) text-danger @endif ">
                                                Rp. {{ number_format($kasbon->sisa_kasbon, 0, ',', '.') }}</td>
                                            <td>
                                                @if ($kasbon->status == 0)
                                                    <button class="btn btn-warning">Proses</button>
                                                @else
                                                    <button class="btn btn-success">Selesai</button>
                                                @endif
                                            </td>
                                        </tr>
                                    @endforeach

                                </tbody>
                            </table>
                        </div>
                        {{ $kasbons->appends(['search' => $search ?? ''])->links('pagination::bootstrap-5') }}
                    </div>
                </div>
            </div>
            <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
            <script src="{{ asset('js/all/show.js') }}"></script>
        </x-slot>
    </x-bar.navbar>
@endsection
