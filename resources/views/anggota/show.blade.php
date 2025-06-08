@extends('layouts.aplication')
@section('title', 'Detail Anggota')
@section('content')
    <x-bar.navbar>Halaman Anggota
        <x-slot name="content">
            <div class="container mt-4">
                <div class="row">
                    <!-- Main Card -->
                    <div class="col-md-9 mb-3">
                        <x-alert-message></x-alert-message>
                        <div class="card shadow">
                            <div class="card-header bg-primary text-white">
                                <h5 class="mb-0">Informasi Anggota</h5>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-6">
                                        <p class="mb-1"><strong>No Anggota</strong></p>
                                        <p>{{ $anggota->no_anggota }}</p>
                                    </div>
                                    <div class="col-md-6">
                                        <p class="mb-1"><strong>Nama</strong></p>
                                        <p>{{ $anggota->nama }}</p>
                                    </div>
                                </div>

                                <div class="row mt-3">
                                    <div class="col-md-6">
                                        <p class="mb-1"><strong>Tanggal Lahir</strong></p>
                                        <p>{{ \Carbon\Carbon::parse($anggota->tanggal_lahir)->translatedFormat('d F Y') }}
                                        </p>
                                    </div>
                                    <div class="col-md-6">
                                        <p class="mb-1"><strong>Nomor Handphone</strong></p>
                                        <p>{{ $anggota->no_hp }}</p>
                                    </div>
                                </div>

                                <div class="row mt-3">
                                    <div class="col-md-6">
                                        <p class="mb-1"><strong>Alamat KTP</strong></p>
                                        <p>{{ $anggota->alamat_ktp }}</p>
                                    </div>
                                    <div class="col-md-6">
                                        <p class="mb-1"><strong>Alamat Domisili</strong></p>
                                        <p>{{ $anggota->alamat_domisili }}</p>
                                    </div>
                                </div>

                                <div class="row mt-3">
                                    <div class="col-md-6">
                                        <p class="mb-1"><strong>Tanggal Daftar</strong></p>
                                        <p>{{ \Carbon\Carbon::parse($anggota->tanggal_daftar)->translatedFormat('d F Y') }}
                                        </p>
                                    </div>
                                    <div class="col-md-6">
                                        <p class="mb-1"><strong>Simpanan</strong></p>
                                        <p>Rp. {{ number_format($anggota->simpanan, 0, ',', '.') }}</p>
                                    </div>
                                </div>

                                <div class="mt-4">
                                    <a href="{{ route('anggota.index') }}" class="btn btn-secondary"><i
                                            class="ri-home-4-line"></i></a>
                                    <a class="btn btn-success"
                                        href="https://www.google.com/maps?q={{ $anggota->latitude }},{{ $anggota->longitude }}"
                                        target="_blank">
                                        <i class="ri-map-pin-user-line"></i>
                                    </a>

                                    @if (auth()->check() && auth()->user()->role === 'admin')
                                        <a href="{{ route('anggota.edit', $anggota->id) }}" class="btn btn-warning "><i
                                                class="ri-pencil-fill"></i></a>
                                        <button type="button" class="btn btn-danger" data-bs-toggle="modal"
                                            data-bs-target="#deleteModal{{ $anggota->id }}">
                                            <i class="ri-delete-bin-2-fill"></i>
                                        </button>
                                        <button type="button" class="btn btn-primary" data-bs-toggle="modal"
                                            data-bs-target="#uploadModal{{ $anggota->id }}">
                                            <i class="ri-image-line"></i>
                                        </button>
                                        <button type="button" class="btn btn-dark" data-bs-toggle="modal"
                                            data-bs-target="#lockConfirmationModal{{ $anggota->id }}">
                                            @if ($anggota->is_locked)
                                                <i class="ri-lock-unlock-line"></i>
                                            @else
                                                <i class="ri-lock-2-fill"></i>
                                            @endif
                                        </button>
                                    @elseif (auth()->check() && auth()->user()->role === 'marketing')
                                        @if (!$anggota->is_locked)
                                            <a href="{{ route('anggota.edit', $anggota->id) }}" class="btn btn-warning "><i
                                                    class="ri-pencil-fill"></i></a>
                                            <button type="button" class="btn btn-danger" data-bs-toggle="modal"
                                                data-bs-target="#deleteModal{{ $anggota->id }}">
                                                <i class="ri-delete-bin-2-fill"></i>
                                            </button>
                                            <button type="button" class="btn btn-primary" data-bs-toggle="modal"
                                                data-bs-target="#uploadModal{{ $anggota->id }}">
                                                <i class="ri-image-line"></i>
                                            </button>
                                            <button type="button" class="btn btn-dark" data-bs-toggle="modal"
                                                data-bs-target="#lockConfirmationModal{{ $anggota->id }}"><i
                                                    class="ri-lock-2-fill"></i></button>
                                        @endif
                                    @endif
                                </div>
                                @include('anggota.modal.kunci', ['anggota' => $anggota])
                                @include('anggota.modal.hapus', ['anggota' => $anggota])
                                @include('anggota.modal.upload', ['anggota' => $anggota])
                            </div>
                        </div>
                    </div>

                    <div class="col-md-3 mb-5">
                        <div class="card shadow">
                            <div class="card-header bg-primary d-flex justify-content-between align-items-center">
                                <h5 class="mb-0 text-white">Foto KTP</h5>
                                @if ($anggota->foto_ktp)
                                    <a href="{{ asset('storage/' . $anggota->foto_ktp) }}" download
                                        class="btn btn-sm btn-light">
                                        <i class="ri-download-2-line"></i>
                                    </a>
                                @endif
                            </div>
                            <div class="card-body text-center">
                                @if ($anggota->foto_ktp)
                                    <img src="{{ asset('storage/' . $anggota->foto_ktp) }}" class="img-fluid mb-3"
                                        style="max-width: 100%; height: auto; margin-bottom: 1rem;" alt="Foto KTP">
                                @else
                                    <p class="text-muted">Tidak ada foto KTP.</p>
                                @endif
                            </div>
                        </div>

                        <div class="card shadow mt-3">
                            <div class="card-header bg-primary d-flex justify-content-between align-items-center">
                                <h5 class="mb-0 text-white">Foto KK</h5>
                                @if ($anggota->foto_kk)
                                    <a href="{{ asset('storage/' . $anggota->foto_kk) }}" download
                                        class="btn btn-sm btn-light">
                                        <i class="ri-download-2-line"></i>
                                    </a>
                                @endif
                            </div>
                            <div class="card-body text-center">
                                @if ($anggota->foto_kk)
                                    <img src="{{ asset('storage/' . $anggota->foto_kk) }}" class="img-fluid mb-3"
                                        style="max-width: 100%; height: auto; margin-bottom: 1rem;" alt="Foto KK">
                                @else
                                    <p class="text-muted">Tidak ada foto Kartu Keluarga.</p>
                                @endif
                            </div>
                        </div>

                        <!-- Modifikasi HTML Anda -->
                        <div class="card shadow mt-3">
                            <div class="card-header bg-primary d-flex justify-content-between align-items-center">
                                <h5 class="mb-0 text-white">BARCODE</h5>
                                @if ($anggota->no_anggota)
                                    <a href="javascript:void(0)" onclick="downloadQRCode()" class="btn btn-sm btn-light">
                                        <i class="ri-download-2-line"></i>
                                    </a>
                                @endif
                            </div>
                            <div class="card-body text-center" id="qr-container">
                                @if ($anggota->no_anggota)
                                    {!! QrCode::size(100)->margin(2)->generate($anggota->no_anggota) !!}
                                @else
                                    <p class="text-muted">Barcode Error</p>
                                @endif
                            </div>
                        </div>

                        <script>
                            function downloadQRCode() {
                                // Ambil SVG element dari QR code yang di-generate Laravel
                                const svgElement = document.querySelector('#qr-container svg');

                                if (!svgElement) {
                                    alert('QR Code tidak ditemukan');
                                    return;
                                }

                                // Buat canvas element
                                const canvas = document.createElement('canvas');
                                const ctx = canvas.getContext('2d');

                                // Set ukuran canvas (sesuaikan dengan size QR code + margin)
                                canvas.width = 120; // 100px + margin
                                canvas.height = 120;

                                // Konversi SVG ke data string
                                const svgData = new XMLSerializer().serializeToString(svgElement);
                                const svgBlob = new Blob([svgData], {
                                    type: 'image/svg+xml;charset=utf-8'
                                });
                                const url = URL.createObjectURL(svgBlob);

                                // Buat image element untuk load SVG
                                const img = new Image();

                                img.onload = function() {
                                    // Set background putih
                                    ctx.fillStyle = 'white';
                                    ctx.fillRect(0, 0, canvas.width, canvas.height);

                                    // Gambar SVG ke canvas
                                    ctx.drawImage(img, 10, 10, 100, 100);

                                    // Convert canvas ke PNG dan download
                                    const link = document.createElement('a');
                                    link.download = 'qrcode-{{ $anggota->no_anggota ?? 'member' }}.png';
                                    link.href = canvas.toDataURL('image/png', 1.0);

                                    // Trigger download
                                    document.body.appendChild(link);
                                    link.click();
                                    document.body.removeChild(link);

                                    // Cleanup
                                    URL.revokeObjectURL(url);
                                };

                                img.onerror = function() {
                                    alert('Error saat mengkonversi QR Code');
                                    URL.revokeObjectURL(url);
                                };

                                // Load SVG sebagai image
                                img.src = url;
                            }
                        </script>
                    </div>

                </div>
            </div>
        </x-slot>
    </x-bar.navbar>
@endsection
