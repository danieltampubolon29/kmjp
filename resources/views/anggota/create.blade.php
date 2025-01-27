<!doctype html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Create Anggota</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
  </head>
  <body>
    <div class="container">
        <h2>Create Anggota</h2>
        <form action="{{ route('anggota.store') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <!-- No Anggota -->
            <div class="mb-3">
                <label for="no_anggota" class="form-label">No Anggota</label>
                <input type="text" class="form-control" id="no_anggota" name="no_anggota" required>
            </div>

            <!-- Nama -->
            <div class="mb-3">
                <label for="nama" class="form-label">Nama</label>
                <input type="text" class="form-control" id="nama" name="nama" required>
            </div>

            <!-- Tanggal Lahir -->
            <div class="mb-3">
                <label for="tanggal_lahir" class="form-label">Tanggal Lahir</label>
                <input type="date" class="form-control" id="tanggal_lahir" name="tanggal_lahir" required>
            </div>

            <!-- Alamat KTP -->
            <div class="mb-3">
                <label for="alamat_ktp" class="form-label">Alamat KTP</label>
                <textarea class="form-control" id="alamat_ktp" name="alamat_ktp" required></textarea>
            </div>

            <!-- Alamat Domisili -->
            <div class="mb-3">
                <label for="alamat_domisili" class="form-label">Alamat Domisili</label>
                <textarea class="form-control" id="alamat_domisili" name="alamat_domisili" required></textarea>
            </div>

            <!-- No HP -->
            <div class="mb-3">
                <label for="no_hp" class="form-label">No HP</label>
                <input type="text" class="form-control" id="no_hp" name="no_hp" required>
            </div>

            <!-- Pekerjaan -->
            <div class="mb-3">
                <label for="pekerjaan" class="form-label">Pekerjaan</label>
                <input type="text" class="form-control" id="pekerjaan" name="pekerjaan" required>
            </div>

            <!-- Marketing -->
            <div class="mb-3">
                <label for="marketing" class="form-label">Marketing</label>
                <input type="text" class="form-control" id="marketing" name="marketing" required>
            </div>

            <!-- Lokasi -->
            <div class="mb-3">
                <label for="lokasi" class="form-label">Lokasi</label>
                <input type="text" class="form-control" id="lokasi" name="lokasi" required>
            </div>

            <!-- Foto KTP -->
            <div class="mb-3">
                <label for="foto_ktp" class="form-label">Foto KTP</label>
                <input type="file" class="form-control" id="foto_ktp" name="foto_ktp" accept="image/*">
            </div>

            <!-- Foto KK -->
            <div class="mb-3">
                <label for="foto_kk" class="form-label">Foto KK</label>
                <input type="file" class="form-control" id="foto_kk" name="foto_kk" accept="image/*">
            </div>

            <!-- Submit Button -->
            <button type="submit" class="btn btn-primary">Submit</button>
        </form>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
  </body>
</html>
