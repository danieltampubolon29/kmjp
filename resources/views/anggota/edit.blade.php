<!doctype html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>create</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
  </head>
  <body>
<div class="container">
    <h2>Edit Anggota</h2>
    <form action="{{ route('anggota.update', $anggota->id) }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')

        <div class="mb-3">
            <label for="no_anggota" class="form-label">No Anggota</label>
            <input type="text" class="form-control" id="no_anggota" name="no_anggota" value="{{ old('no_anggota', $anggota->no_anggota) }}" required>
        </div>
        <div class="mb-3">
            <label for="nama" class="form-label">Nama</label>
            <input type="text" class="form-control" id="nama" name="nama" value="{{ old('nama', $anggota->nama) }}" required>
        </div>
        <div class="mb-3">
            <label for="tanggal_lahir" class="form-label">Tanggal Lahir</label>
            <input type="date" class="form-control" id="tanggal_lahir" name="tanggal_lahir" value="{{ old('tanggal_lahir', $anggota->tanggal_lahir) }}" required>
        </div>
        <div class="mb-3">
            <label for="alamat_ktp" class="form-label">Alamat KTP</label>
            <textarea class="form-control" id="alamat_ktp" name="alamat_ktp" required>{{ old('alamat_ktp', $anggota->alamat_ktp) }}</textarea>
        </div>
        <div class="mb-3">
            <label for="alamat_domisili" class="form-label">Alamat Domisili</label>
            <textarea class="form-control" id="alamat_domisili" name="alamat_domisili" required>{{ old('alamat_domisili', $anggota->alamat_domisili) }}</textarea>
        </div>
        <div class="mb-3">
            <label for="no_hp" class="form-label">No HP</label>
            <input type="text" class="form-control" id="no_hp" name="no_hp" value="{{ old('no_hp', $anggota->no_hp) }}" required>
        </div>
        <div class="mb-3">
            <label for="pekerjaan" class="form-label">Pekerjaan</label>
            <input type="text" class="form-control" id="pekerjaan" name="pekerjaan" value="{{ old('pekerjaan', $anggota->pekerjaan) }}" required>
        </div>
        <div class="mb-3">
            <label for="marketing" class="form-label">Marketing</label>
            <input type="text" class="form-control" id="marketing" name="marketing" value="{{ old('marketing', $anggota->marketing) }}" required>
        </div>
        <div class="mb-3">
            <label for="lokasi" class="form-label">Lokasi</label>
            <input type="text" class="form-control" id="lokasi" name="lokasi" value="{{ old('lokasi', $anggota->lokasi) }}" required>
        </div>

        <div class="mb-3">
            <label for="foto_ktp" class="form-label">Foto KTP</label>
            <input type="file" class="form-control" id="foto_ktp" name="foto_ktp">
            @if($anggota->foto_ktp)
                <img src="{{ asset('storage/' . $anggota->foto_ktp) }}" alt="Foto KTP" width="100" class="mt-2">
            @endif
        </div>

        <div class="mb-3">
            <label for="foto_kk" class="form-label">Foto KK</label>
            <input type="file" class="form-control" id="foto_kk" name="foto_kk">
            @if($anggota->foto_kk)
                <img src="{{ asset('storage/' . $anggota->foto_kk) }}" alt="Foto KK" width="100" class="mt-2">
            @endif
        </div>

        <button type="submit" class="btn btn-primary">Update Anggota</button>
    </form>
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>

</body>
</html>
