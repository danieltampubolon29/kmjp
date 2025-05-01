@extends('layouts.aplication')
@section('title', 'Progres')
@section('content')
    <x-bar.navbar>Target Harian
        <x-slot name="content">
            <div class="container mt-4">
                <div class="card shadow">
                    <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
                        <h5 class="py-1 mb-0">Daftar Jatuh Tempo {{ $currentDay }}</h5>
                    </div>
                    <div class="card-body">
                        <x-alert-message></x-alert-message>
                        <div class="table-responsive">
                            <table class="table table-light table-striped table-hover text-center">
                                <thead>
                                    <tr class="table-dark text-center align-middle">
                                        <th>NO</th>
                                        <th>NO ANGGOTA</th>
                                        <th>NAMA</th>
                                        <th>NOMINAL</th>
                                        <th>STATUS</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @if ($paginatedPencairans->isEmpty())
                                        <tr>
                                            <td colspan="5" class="text-center">
                                                <p>Target angsuran jatuh tempo hari {{ $currentDay }} sudah tidak tersedia.</p>
                                            </td>
                                        </tr>
                                    @else
                                        @foreach ($paginatedPencairans as $pencairan)
                                            <tr data-href="{{ route('angsuran.create', ['pencairan_id' => $pencairan->id]) }}"
                                                data-pencairan-id="{{ $pencairan->id }}">
                                                <td>{{ $loop->iteration }}</td>
                                                <td>{{ $pencairan->anggota->no_anggota }}</td>
                                                <td>{{ $pencairan->anggota->nama }}</td>
                                                <td>Rp. {{ number_format($pencairan->nominal, 0, ',', '.') }},-</td>
                                                <td>
                                                    @if ($pencairan->status == 1)
                                                        <button
                                                            class="btn btn-success btn-sm text-light fw-bold">Lunas</button>
                                                    @elseif($pencairan->status == 0)
                                                        <button
                                                            class="btn btn-warning btn-sm text-light fw-bold">Proses</button>
                                                    @endif
                                                </td>
                                            </tr>
                                        @endforeach
                                    @endif
                                </tbody>
                            </table>
                        </div>
                        {{ $paginatedPencairans->appends(['search' => $search ?? ''])->links('pagination::bootstrap-5') }}
                    </div>
                </div>
            </div>
            <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
            <script src="{{ asset('js/progres/targetHarian/click.js') }}"></script>
            <script src="{{ asset('js/all/show.js') }}"></script>
        </x-slot>
    </x-bar.navbar>
@endsection
