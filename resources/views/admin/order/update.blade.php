<div class="modal fade" id="updateStatusModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header bg-gradient-primary text-white">
                <h5 class="modal-title">Konfirmasi Update Status</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>

            <div class="modal-body text-center py-4">
                <i class="mdi mdi-help-circle-outline fs-1 text-warning"></i>
                <p class="mt-2 mb-0" id="confirmMessage">Apakah kamu yakin ingin mengubah status order ini?</p>
            </div>

            <div class="modal-footer">
                {{-- Tombol Batal - hanya dismiss modal --}}
                <button type="button" class="btn btn-light" data-bs-dismiss="modal">Batal</button>

                {{-- Tombol Ya - submit form --}}
                <button type="button" class="btn btn-primary" id="btnConfirmYes">Ya, Ubah</button>
            </div>
        </div>
    </div>
</div>

{{-- Form hidden, tidak tampil di UI --}}
<form id="updateStatusForm" method="POST" style="display: none;">
    @csrf
    @method('PUT')
    <input type="hidden" name="status" id="orderStatus">
</form>
