@extends('layouts.aplication')
@section('title', 'Progres')
@section('content')
<x-bar.navbar>Rekap Data
    <x-slot name="content">
        <style>
            .card-footer {
                padding: 0;
                margin-top: auto;
            }

            .card-footer .row {
                align-items: center;
            }

            .card-footer .col-6,
            .card-footer .col-1 {
                padding: 0.5rem;
                display: flex;
                align-items: center;
            }
        </style>

        <div class="container mb-5">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <button class="btn btn-primary" id="downloadBtn">
                        <i class="ri-download-2-line"></i>
                    </button>
                    <div class="d-flex gap-2">
                        <select id="monthSelect" class="form-select">
                            @for ($i = 1; $i <= 12; $i++)
                                <option value="{{ $i }}">{{ date('F', mktime(0, 0, 0, $i, 1)) }}</option>
                            @endfor
                        </select>
                        <input type="number" id="yearInput" class="form-control" placeholder="Tahun" min="2000"
                            max="2100" />
                    </div>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table id="dynamicTable" class="table table-bordered">
                            <thead id="tableHeader">
                                <tr></tr>
                                <tr id="tableSubHeader"></tr>
                            </thead>
                            <tbody id="tableBody"></tbody>
                            <tfoot id="tableFooter">
                                <tr></tr>
                            </tfoot>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <script src="https://cdnjs.cloudflare.com/ajax/libs/html2canvas/1.4.1/html2canvas.min.js"></script>
        <script src="{{ asset('js/progres/rekap-marketing.js') }}"></script>
    </x-slot>
</x-bar.navbar>
@endsection
