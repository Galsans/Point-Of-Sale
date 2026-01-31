<div class="modal fade" id="detailMenuModal" tabindex="-1">
    <div class="modal-dialog modal-lg modal-dialog-centered modal-dialog-scrollable">
        <div class="modal-content">

            <div class="modal-header bg-gradient-primary text-white">
                <h5 class="modal-title"><i class="mdi mdi-food"></i> Detail Menu</h5>
                <button class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>

            <div class="modal-body px-3">
                <div class="row g-3">

                    <!-- IMAGE MENU -->
                    <div class="col-md-5 text-center">
                        <img id="detailMenuImage" src="" alt="Menu Image" class="img-fluid rounded shadow-sm"
                            style="max-height: 250px; object-fit: cover;">
                    </div>

                    <!-- INFO MENU -->
                    <div class="col-md-7">
                        <h5 id="detailMenuName" class="fw-bold mb-2"></h5>

                        <p class="mb-1"><strong>Kategori:</strong> <span id="detailMenuCategory"></span></p>
                        <p class="mb-1"><strong>Harga:</strong> Rp <span id="detailMenuPrice"></span></p>
                        <p class="mb-1"><strong>Status:</strong>
                            <span id="detailMenuStatus" class="badge"></span>
                        </p>
                        <p class="mb-1"><strong>Deskripsi:</strong></p>
                        <p id="detailMenuDescription" class="text-muted"></p>
                    </div>

                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-light" data-bs-dismiss="modal">Tutup</button>
            </div>
        </div>
    </div>
</div>
