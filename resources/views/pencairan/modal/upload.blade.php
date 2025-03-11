    <div class="modal fade" id="uploadModal{{ $pencairan->id }}" tabindex="-1" aria-labelledby="uploadModal"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="uploadModal">Upload Foto</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form id="uploadForm" action="{{ route('pencairan.upload', $pencairan->id) }}" method="POST"
                    enctype="multipart/form-data">
                    @csrf
                    <div class="modal-body">
                        <div class="mb-3">
                            <label for="foto_pencairan" class="form-label">Foto Pencairan</label>
                            <input type="file" name="foto_pencairan" id="foto_pencairan" class="form-control" accept="image/*"
                                onchange="previewImage('foto_pencairan', 'previewPencairan')">
                            <div class="mt-2">
                                <img id="previewPencairan" src="#" alt="Preview Pencairan"
                                    style="max-width: 100px; display: none;" />
                            </div>
                        </div>
                        <div class="mb-3">
                            <label for="foto_rumah" class="form-label">Foto Rumah</label>
                            <input type="file" name="foto_rumah" id="foto_rumah" class="form-control" accept="image/*"
                                onchange="previewImage('foto_rumah', 'previewRumah')">
                            <div class="mt-2">
                                <img id="previewRumah" src="#" alt="Preview Rumah"
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
