<script>
    document.addEventListener('DOMContentLoaded', () => {
        const modal = document.getElementById('optionModal');
        const optionList = document.getElementById('optionList');
        const optionGroupName = document.getElementById('optionGroupName');

        if (!modal) return;

        modal.addEventListener('show.bs.modal', (event) => {
            const button = event.relatedTarget;

            if (!button) return;

            const groupId = button.dataset.id;
            const groupName = button.dataset.name;

            // Set judul modal
            optionGroupName.textContent = groupName;

            // Loading state
            optionList.innerHTML = `
            <div class="col-12 text-center py-4">
                <div class="spinner-border text-primary"></div>
                <p class="mt-2 text-muted">Memuat pilihan...</p>
            </div>
        `;

            fetch(`/option-groups/${groupId}/options`)
                .then(res => {
                    if (!res.ok) throw new Error('Gagal mengambil data');
                    return res.json();
                })
                .then(options => {
                    if (!options.length) {
                        optionList.innerHTML = `
                        <div class="col-12 text-center py-4">
                            <i class="mdi mdi-alert-circle-outline mdi-36px text-muted"></i>
                            <p class="text-muted mt-2">Tidak ada option tersedia</p>
                        </div>
                    `;
                        return;
                    }

                    let html = '';

                    options.forEach(option => {
                        const price = option.extra_price ?
                            `+ Rp ${Number(option.extra_price).toLocaleString()}` :
                            'Tanpa biaya';

                        html += `
                        <div class="col-md-4 col-sm-6 mb-3">
                            <label class="option-card w-100">
                                <input
                                    type="radio"
                                    name="option_${groupId}"
                                    value="${option.id}"
                                    class="d-none"
                                >

                                <div class="card h-100 option-item text-center shadow-sm">
                                    <div class="card-body py-3">
                                        <i class="mdi mdi-check-circle option-check"></i>

                                        <h6 class="fw-bold mb-1">
                                            ${option.name}
                                        </h6>

                                        <span class="badge ${
                                            option.extra_price ? 'bg-primary' : 'bg-light text-muted'
                                        } mt-2">
                                            ${price}
                                        </span>
                                    </div>
                                </div>
                            </label>
                        </div>
                    `;
                    });

                    optionList.innerHTML = html;
                })
                .catch(() => {
                    optionList.innerHTML = `
                    <div class="col-12 text-center py-4">
                        <i class="mdi mdi-close-circle-outline mdi-36px text-danger"></i>
                        <p class="text-danger mt-2">Gagal memuat option</p>
                    </div>
                `;
                });
        });
    });
</script>

<script>
    document.addEventListener('DOMContentLoaded', () => {
        document.querySelectorAll('.btn-edit-option-group').forEach(button => {
            button.addEventListener('click', function() {

                const id = this.dataset.id;

                const name = this.dataset.name ?? '';
                const type = this.dataset.type ?? '';
                const description = this.dataset.description ?? '';

                // INPUT
                document.getElementById('editOptionGroupName').value = name;
                document.getElementById('editOptionGroupDescription').value = description;

                // 🔥 SELECT (RESET DULU)
                const typeSelect = document.getElementById('editOptionGroupType');
                typeSelect.value = ''; // reset
                typeSelect.dispatchEvent(new Event('change'));

                // 🔥 SET VALUE (HARUS MATCH OPTION)
                typeSelect.value = type.trim(); // trim penting
                typeSelect.dispatchEvent(new Event('change'));

                // FORM ACTION
                document.getElementById('editOptionGroupForm').action =
                    `/option-groups/${id}`;
            });
        });

        document.querySelectorAll('.btn-delete-option-group').forEach(btn => {
            btn.addEventListener('click', function() {
                document.getElementById('deleteOptionGroupName').innerText = this.dataset.name;
                document.getElementById('deleteOptionGroupForm').action =
                    `/option-groups/${this.dataset.id}`;
            });
        });

    });
</script>
