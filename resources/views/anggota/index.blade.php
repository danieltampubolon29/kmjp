<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>create</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
</head>

<body>
    <div class="container">
        <h2>Daftar Anggota</h2>
        <a href="{{ route('anggota.create') }}" class="btn btn-primary mb-3">Tambah Anggota</a>

        <!-- Success or Error Messages -->
        
        @if (session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @elseif(session('error'))
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                {{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif


        <!-- Table to display the list of Anggota -->
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>No Anggota</th>
                    <th>Nama</th>
                    <th>Tanggal Lahir</th>
                    <th>Alamat KTP</th>
                    <th>Alamat Domisili</th>
                    <th>No HP</th>
                    <th>Pekerjaan</th>
                    <th>Lokasi</th>
                    <th>Foto KTP</th>
                    <th>Foto KK</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($anggotas as $item)
                    <tr>
                        <td>{{ $item->no_anggota }}</td>
                        <td>{{ $item->nama }}</td>
                        <td>{{ \Carbon\Carbon::parse($item->tanggal_lahir)->format('d-m-Y') }}</td>
                        <td>{{ $item->alamat_ktp }}</td>
                        <td>{{ $item->alamat_domisili }}</td>
                        <td>{{ $item->no_hp }}</td>
                        <td>{{ $item->pekerjaan }}</td>
                        <td>{{ $item->lokasi }}</td>
                        <td>
                            @if ($item->foto_ktp)
                                <img src="{{ asset('storage/' . $item->foto_ktp) }}" alt="Foto KTP" width="50">
                            @endif
                        </td>
                        <td>
                            @if ($item->foto_kk)
                                <img src="{{ asset('storage/' . $item->foto_kk) }}" alt="Foto KK" width="50">
                            @endif
                        </td>
                        <td>
                            <a href="{{ route('anggota.edit', $item->id) }}" class="btn btn-warning btn-sm">Edit</a>
                            <form action="{{ route('anggota.destroy', $item->id) }}" method="POST"
                                style="display:inline-block;">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger btn-sm"
                                    onclick="return confirm('Apakah Anda yakin ingin menghapus anggota ini?')">Hapus</button>
                            </form>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous">
    </script>

</body>

</html>
