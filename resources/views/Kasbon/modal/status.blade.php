<div class="modal fade" id="statusModal{{ $kasbon->id }}" tabindex="-1"
    aria-labelledby="statusModalLabel{{ $kasbon->id }}" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="statusModalLabel{{ $kasbon->id }}">
                    Konfirmasi Status
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form action="{{ route('kasbon.status', $kasbon->id) }}" method="POST" style="display:inline;">
                    @csrf
                    @method('PUT')
                    <p>Setelah ini status kasbon akan selesai yang menunjukkan kasbon sudah selesai</p>
                    <div class="mb-3">
                        <label for="sisa_kasbon{{ $kasbon->id }}" class="form-label">Sisa Kasbon Marketing</label>
                        <input type="number" class="form-control" id="sisa_kasbon{{ $kasbon->id }}"
                        name="sisa_kasbon" placeholder="Masukkan Sisa Kasbon" required>
                    </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                <button type="submit" class="btn btn-success">
                    <i class="ri-save-line"></i>
                </button>
                </form>
            </div>
        </div>
    </div>
</div>
