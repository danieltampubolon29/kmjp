@extends('layouts.aplication')
@section('title', 'Validasi')
@section('content')
<x-bar.navbar>Validasi Angsuran
    <x-slot name="content">
        <div class="container mt-4">
            <x-alert-message></x-alert-message>
            <div class="card shadow">
                <div class="card-body">
                    <button class="btn btn-primary mb-3" data-bs-toggle="modal" data-bs-target="#validasiModal">
                        Validasi Semua Data
                    </button>
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
                                @foreach ($datas as $angsuran)
                                    <tr>
                                        <td>{{ $loop->iteration }}</td>
                                        <td>{{ $angsuran->pencairan->no_anggota }}</td>
                                        <td>{{ $angsuran->pencairan->nama }}</td>
                                        <td>Rp. {{ number_format($angsuran->nominal, 0, ',', '.') }},-</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    {{ $datas->links('pagination::bootstrap-5') }}
                </div>
            </div>
        </div>

        <div class="modal fade" id="validasiModal" tabindex="-1" aria-labelledby="validasiModalLabel"
                aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered">
                    <div class="modal-content">
                        <form action="{{ route('validasi.semua-angsuran') }}" method="POST">
                            @csrf
                            <div class="modal-header bg-primary text-white">
                                <h5 class="modal-title" id="validasiModalLabel">Konfirmasi Validasi</h5>
                                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"
                                    aria-label="Close"></button>
                            </div>
                            <div class="modal-body">
                                <h6>Pilih tanggal untuk validasi. Setelah validasi, data angsuran tidak bisa diubah dan akan
                                    masuk ke laporan Anda sesuai tanggal yang Anda pilih.</h6>
                                <div class="mb-3">
                                    <label for="tanggal_laporan" class="form-label">Tanggal Laporan</label>
                                    <input type="date" class="form-control" id="tanggal_laporan" name="tanggal_laporan"
                                        required>
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                                <button type="submit" class="btn btn-primary">Validasi</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

        <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    </x-slot>
</x-bar.navbar>
@endsection