    <div class="modal fade" id="uploadModal{{ $anggota->id }}" tabindex="-1" aria-labelledby="uploadModal"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="uploadModal">Upload Foto</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form id="uploadForm" action="{{ route('anggota.upload', $anggota->id) }}" method="POST"
                    enctype="multipart/form-data">
                    @csrf
                    <div class="modal-body">
                        <div class="mb-3">
                            <label for="foto_ktp" class="form-label">Foto KTP</label>
                            <input type="file" name="foto_ktp" id="foto_ktp" class="form-control" accept="image/*"
                                onchange="previewImage('foto_ktp', 'previewKtp')">
                            <div class="mt-2">
                                <img id="previewKtp" src="#" alt="Preview KTP"
                                    style="max-width: 100px; display: none;" />
                            </div>
                        </div>
                        <div class="mb-3">
                            <label for="foto_kk" class="form-label">Foto KK</label>
                            <input type="file" name="foto_kk" id="foto_kk" class="form-control" accept="image/*"
                                onchange="previewImage('foto_kk', 'previewKk')">
                            <div class="mt-2">
                                <img id="previewKk" src="#" alt="Preview KK"
                                    style="max-width: 100px; display: none;" />
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-primary">Simpan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
