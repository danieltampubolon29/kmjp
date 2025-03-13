<div class="modal fade" id="lockConfirmationModal{{ $simpanan->id }}" tabindex="-1"
    aria-labelledby="lockConfirmationModalLabel" aria-hidden="true">
   <div class="modal-dialog modal-dialog-centered">
       <div class="modal-content">
           <div class="modal-header">
               <h5 class="modal-title" id="lockConfirmationModalLabel">
                   Konfirmasi {{ $simpanan->is_locked ? 'Buka Kunci' : 'Kunci' }}
               </h5>
               <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
           </div>
           <div class="modal-body">
               @if ($simpanan->is_locked)
                   Apakah Anda yakin ingin membuka kunci data ini? Setelah dibuka, data dapat diedit atau dihapus.
               @else
                   Apakah Anda yakin ingin mengunci data ini? Setelah dikunci, data tidak dapat diedit atau dihapus.
               @endif
           </div>
           <div class="modal-footer">
               <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
               <form action="{{ route('simpanan.lock', $simpanan->id) }}" method="POST" style="display:inline;">
                   @csrf
                   <button type="submit" class="btn btn-success">
                       {{ $simpanan->is_locked ? 'Buka Kunci' : 'Kunci' }}
                   </button>
               </form>
           </div>
       </div>
   </div>
</div>