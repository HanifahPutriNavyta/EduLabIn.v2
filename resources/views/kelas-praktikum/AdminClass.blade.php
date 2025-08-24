@extends('layouts.app')
@section('title', 'Kelas Praktikum - EduLabIn')

@section('content')
<main class="container py-4">
    <h1 class="page-title">Kelas Praktikum</h1>

    <div class="search-container mb-3 position-relative">
        <input type="text" class="form-control search-input" placeholder="Cari Mata Kuliah, Kelas, atau Kode Enroll..." aria-label="Search">
        <i class="bi bi-search search-icon position-absolute top-50 end-0 translate-middle-y me-3"></i>
    </div>

    <hr class="my-4">
    @php
    $columns = [
    ['key' => 'nama_mk', 'label' => 'Mata Kuliah'],
    ['key' => 'kode_kelas', 'label' => 'Kelas'],
    ['key' => 'kode_enroll', 'label' => 'Kode Enroll'],
    ];

    @endphp
    <div id="classCardsContainer">
        @foreach($kelasPraktikums as $class)
        <x-admin.class-card
            :card-id="$class->kelas_id"
            :title="($class->mataKuliah->nama_mk . ' - ' . $class->kode_kelas)"
            :class-count="$class->mataKuliah->pendaftaranAspraks->kuota"
            :mk-id="$class->mk_id"
            :data="$class"/>
        @endforeach
    </div>

    <div id="noResultsMessage" class="text-center mt-4" style="display: none;">
        <p class="text-muted">Tidak ada kelas praktikum yang ditemukan untuk mata kuliah ini.</p>       
    </div>

  


    <!-- Template for new cards -->
    <template id="classCardTemplate">
        @php
    $templateData = [
    'id' => 'TEMPLATE_ID',
    'title' => 'TEMPLATE_TITLE',
    'classCount' => 0,
    'mkId' => 'TEMPLATE_MK_ID',
    'data' => [
    'id' => 'TEMPLATE_ID',
    'title' => 'TEMPLATE_TITLE',
    'classCount' => 0,
    'mkId' => 'TEMPLATE_MK_ID',
    ]
    ];
        @endphp
        <x-admin.class-card
            :card-id="$templateData['id']"
            :title="$templateData['title']"
            
            :class-count="$templateData['classCount']"
            :mk-id="$templateData['mkId']"
            :data="$templateData['data']" />
    </template>

    <button class="fab-button" onclick="openAddClassDialog()">
        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="#222" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
            <line x1="12" y1="5" x2="12" y2="19"></line>
            <line x1="5" y1="12" x2="19" y2="12"></line>
        </svg>
    </button>

    <x-admin.edit-class-dialog />

    <x-admin.add-class-dialog :mataKuliahs="$mataKuliahs" />

    <!-- Delete Confirmation Modal -->
    <div class="modal fade" id="deleteConfirmModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Konfirmasi Hapus</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <p>Hapus kelas praktikum: <strong id="deleteClassName"></strong>?</p>
                    <p class="text-muted mb-0">Tindakan ini tidak dapat dibatalkan.</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="button" class="btn btn-danger" onclick="confirmDelete()">Hapus</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Preview PDF -->
    <div class="modal fade" id="pdfModal" tabindex="-1" aria-labelledby="pdfModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header" style="z-index: 1051; background: #fff;">
                    <h5 class="modal-title" id="pdfModalLabel">Preview PDF</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close" style="z-index: 1052;"></button>
                </div>
                <div class="modal-body text-center py-4">
                    <iframe id="pdfPreviewFrame" src="" width="100%" height="500px" style="border: none; display: none;"></iframe>
                    <div id="noPreview" style="display: none;">
                        <i class="bi bi-file-earmark-pdf pdf-icon-large"></i>
                        <p class="mt-3" id="modalPertemuanText"></p>
                        <p class="text-muted">Preview hanya tersedia untuk file PDF.</p>
                    </div>
                </div>
                <div class="modal-footer justify-content-end">
                    <a href="#" class="btn btn-warning" id="downloadPdfBtn" target="_blank" rel="noopener" download>
                        <i class="bi bi-download"></i> Download
                    </a>
                </div>
            </div>
        </div>
    </div>
</main>
@endsection

@push('styles')
<style>
    .fab-button {
        position: fixed;
        bottom: 2rem;
        right: 2rem;
        width: 56px;
        height: 56px;
        border-radius: 12px;
        background-color: var(--secondary);
        border: none;
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
        color: white;
        display: flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
        transition: transform 0.2s, box-shadow 0.2s;
        z-index: 99999 !important;
        pointer-events: auto !important;
    }
    .fab-button:hover {
        transform: translateY(-2px);
        box-shadow: 0 6px 12px rgba(0, 0, 0, 0.3);
        background-color: var(--secondary-orange800);
    }
    .fab-button:active {
        transform: translateY(0);
        box-shadow: 0 2px 4px rgba(0, 0, 0, 0.2);
    }

    /* Add spacing for requirements lines */
    .card-requirements .requirement-item {
        display: block;
        margin-bottom: 4px;
        white-space: pre-line;
    }

.search-container .form-control.search-input{
    max-width: 100%; 
    margin-bottom: 20px;
    padding: 10px 0px 10px 0px;
    border: none !important;
    outline: none !important;
    box-shadow: none !important;
    background-color: var(--neutral-100) !important;
    color: var(--neutral-300) !important;
    border: none !important;
    border-radius: 10px !important;
    box-shadow: 0px 4px 4px rgba(117, 117, 117, 0.5) !important;
    height: 40px;
    padding: 10px 20px 10px 30px;
    font-size: 14px
}

.search-icon {
    pointer-events: none;
    z-index: 2;
    font-size: 1.2rem;
    color: var(--neutral-300);
    padding-right: 10px;
}

.form-control.search-input:focus {
    background-color: var(--neutral-100) !important;
    box-shadow: 0px 4px 4px rgba(117, 117, 117, 0.5) !important;
    color: var(--neutral-300) !important;
    border: none !important;
    outline: none !important;
}

.form-control.search-input:focus + .search-icon {
    color: var(--neutral-300) !important; 
    display: block !important;
}

.search-container .form-control.search-input::placeholder {
    color: var(--neutral-300) !important;
    opacity: 1 !important;
}

</style>
@endpush

@push('scripts')
<script>
    // lightweight toast like on other pages
    function showToast(message, type = 'info', options = {}){
        let c = document.getElementById('toast-container');
        if (!c){
            c = document.createElement('div');
            c.id = 'toast-container';
            c.style.position = 'fixed';
            c.style.top = '1rem';
            c.style.right = '1rem';
            c.style.zIndex = '2000';
            document.body.appendChild(c);
        }
        const toast = document.createElement('div');
        toast.className = `toast align-items-center text-bg-${type} border-0 show`;
        toast.role = 'alert';
        toast.style.minWidth = '220px';
        toast.innerHTML = `<div class="d-flex"><div class="toast-body">${message}</div><button type="button" class="btn-close btn-close-white me-2 m-auto" onclick="this.parentElement.parentElement.remove()"></button></div>`;
        c.appendChild(toast);
        const delay = typeof options.delay === 'number' ? options.delay : 500;
        setTimeout(()=>{ toast.remove(); }, delay);
        if (options && options.reload === true) {
            setTimeout(()=>{ window.location.reload(); }, delay);
        }
    }
    let currentEditingCard = null;
    let cardToDelete = null;
    // Enhanced Filter Functionality
    document.addEventListener('DOMContentLoaded', function() {
        const searchInput = document.querySelector('.search-input');
        const cardsContainer = document.getElementById('classCardsContainer');
        const noResultsMessage = document.getElementById('noResultsMessage');
        const cards = cardsContainer.querySelectorAll('.class-card');

        searchInput.addEventListener('input', function() {
            const searchTerm = this.value.toLowerCase();
            let hasVisibleCards = false;
            cards.forEach(card => {
                const text = card.textContent.toLowerCase();
                if (text.includes(searchTerm)) {
                    card.style.display = 'block';
                    hasVisibleCards = true;
                } else {
                    card.style.display = 'none';
                }
            });
            noResultsMessage.style.display = hasVisibleCards ? 'none' : 'block';
        });
    });

    function removeCard(cardId, cardData) {
        cardToDelete = {
            id: cardId,
            data: cardData
        };

        // Parse the cardData if it's a string (HTML encoded)
        let parsedCardData = cardData;
       
        if (typeof cardData == 'string') {
            // First decode HTML entities
            const textarea = document.createElement('textarea');
            textarea.innerHTML = cardData;
            const unescapedCardData = textarea.value;

            // Clean the string by removing any control characters and extra whitespace
            const cleanedCardData = unescapedCardData
                .replace(/[\x00-\x1F\x7F-\x9F]/g, '') // Remove control characters
                .replace(/\s+/g, ' ') // Normalize whitespace
                .trim();
            try {
                parsedCardData = JSON.parse(cleanedCardData);
            } catch (e) {
                // Fallback: try to extract basic info from the string
                parsedCardData = {
                    kelas_id: cardId,
                    title: 'Kelas Praktikum'
                };
            }
        }

      
        // Show class name in confirmation modal
        const className = parsedCardData.mata_kuliah?.nama_mk + ' - ' + parsedCardData.kode_kelas || parsedCardData.title || 'Kelas Praktikum';
        document.getElementById('deleteClassName').textContent = className;

        // Show confirmation modal
        const modal = new bootstrap.Modal(document.getElementById('deleteConfirmModal'));
        modal.show();
    }

    function confirmDelete() {
        if (!cardToDelete) return;

        const card = document.querySelector(`[data-card-id="${cardToDelete.id}"]`);
        if (card) {
            // Send delete request to server
            @if(Route::has('kelas-praktikum.laboran.destroy'))
                fetch(`{{ route("kelas-praktikum.laboran.destroy", ["kelasPraktikum" => ":id"]) }}`.replace(':id', cardToDelete.id), {
            @else
                console.warn('Route kelas-praktikum.laboran.destroy not defined');
            @endif
                    method: 'DELETE',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Accept': 'application/json'
                    }
                })
                .then(response => {
                    if (response.ok) {
                        return response.text().then(text => {
                            if (!text) return { status: response.status, data: null };
                            try { return { status: response.status, data: JSON.parse(text) }; }
                            catch (e) { return { status: response.status, data: null }; }
                        });
                    }
                    return response.text().then(text => {
                        let msg = text;
                        try { const parsed = JSON.parse(text); if (parsed && parsed.message) msg = parsed.message; } catch (e) {}
                        return Promise.reject({ status: response.status, message: msg });
                    });
                })
                .then(result => {
                    const data = result.data;
                    const ok = (data && data.success) || (result.status >= 200 && result.status < 300 && !data) || result.status === 204;

                    if (ok) {
                        // Animate card removal
                        card.style.transition = 'opacity 0.3s ease-out, transform 0.3s ease-out';
                        card.style.opacity = '0';
                        card.style.transform = 'translateX(100%)';

                        setTimeout(() => {
                            card.remove();
                        }, 300);

                        // Show success message
                        showToast('Kelas praktikum berhasil dihapus!', 'success');
                    } else {
                        showToast((data && data.message) || 'Gagal menghapus kelas praktikum', 'danger');
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    showToast((error && error.message) || 'Terjadi kesalahan saat menghapus kelas praktikum', 'danger');
                })
                .finally(() => {
                    // Close modal and reset
                    const modal = bootstrap.Modal.getInstance(document.getElementById('deleteConfirmModal'));
                    modal.hide();
                    cardToDelete = null;
                });
        }
    }

    function editCard(cardId, cardData) {
        currentEditingCard = {
            id: cardId,
            data: cardData
        };

        document.querySelector('#editClassDialog .dialog-title').textContent = cardData.title;
        const requirementsList = document.querySelector('#editClassDialog .requirements-list');
        // Support multiline requirements in edit dialog
        requirementsList.innerHTML = cardData.mata_kuliah.pendaftaran_aspraks.ketentuan
            .split('\n')
            .map(line => `<p class="mb-1 requirement-item">${line.trim()}</p>`)
            .join('');
        document.querySelector('#editClassDialog .class-count-info p').textContent = `Jumlah Kelas Praktikum: ${cardData.kapasitas}`;

        document.getElementById('courseName').value = cardData.mata_kuliah.nama_mk;
        document.getElementById('classCode').value = cardData.kode_kelas;
        document.getElementById('kodeEnroll').value = cardData.kode_enroll;

        const modal = new bootstrap.Modal(document.getElementById('editClassDialog'));
        modal.show();
    }

    function updateClass() {
        if (!currentEditingCard) return;

        const courseName = document.getElementById('courseName').value;
        const classCode = document.getElementById('classCode').value;
        const kodeEnroll = document.getElementById('kodeEnroll').value;

        // Validation
        if (!courseName.trim()) {
            showToast('Nama mata kuliah tidak boleh kosong', 'danger');
            return;
        }

        if (!classCode.trim()) {
            showToast('Kode kelas tidak boleh kosong', 'danger');
            return;
        }
        if (!kodeEnroll.trim()) {
            showToast('Kode enroll tidak boleh kosong', 'danger');
            return;
        }


        // Prepare data for server
        const updateData = {
            kelas_id: currentEditingCard.data.kelas_id,
            mk_id: currentEditingCard.data.mata_kuliah.mk_id,
            kode_kelas: classCode,
            kode_enroll: kodeEnroll,
            status: 'aktif'
        };

        // Send update request to server
        @if(Route::has('kelas-praktikum.laboran.update'))
            fetch(`{{ route("kelas-praktikum.laboran.update", ["kelasPraktikum" => ":id"]) }}`.replace(':id', currentEditingCard.data.kelas_id), {
        @else
            console.warn('Route kelas-praktikum.laboran.update not defined');
        @endif
                method: 'PUT',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Accept': 'application/json'
                },
                body: JSON.stringify(updateData)
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    // Update the card in DOM
                    const card = document.querySelector(`[data-card-id="${currentEditingCard.id}"]`);
                    if (card) {
                        card.querySelector('.card-title').textContent = courseName + ' - ' + classCode;

                        // Update the card data with all new values
                        const updatedData = {
                            ...currentEditingCard.data,
                            title: courseName,
                            kode_kelas: classCode,
                            kode_enroll: kodeEnroll,
                        };
                        card.querySelector('.btn-edit').setAttribute('data-card-data', JSON.stringify(updatedData));
                    }

                    // Show success message
                    showToast('Kelas praktikum berhasil diperbarui!', 'success');

                } else {
                    showToast(data.message || 'Gagal memperbarui kelas praktikum', 'danger');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                showToast('Terjadi kesalahan saat memperbarui kelas praktikum', 'danger');
            })
            .finally(() => {
                // Close modal and reset
                const modal = bootstrap.Modal.getInstance(document.getElementById('editClassDialog'));
                modal.hide();
                currentEditingCard = null;
            });
    }

    function openAddClassDialog() {
        document.getElementById('addClassForm').reset();
        const modal = new bootstrap.Modal(document.getElementById('addClassDialog'));
        modal.show();
    }

    function addNewClass() {
        const courseSelect = document.getElementById('newCourseName');
        const selectedOption = courseSelect.options[courseSelect.selectedIndex];
        const courseName = selectedOption.text;
        const courseId = selectedOption.value;

        const classCode = document.getElementById('newClassCode').value;
        const kodeEnroll = document.getElementById('newKodeEnroll').value;
        if (!courseId) {
            showToast('Silakan pilih mata kuliah', 'danger');
            return;
        }
        if (!classCode.trim()) {
            showToast('Kode kelas tidak boleh kosong', 'danger');
            return;
        }

        const newCardId = 'card-' + Date.now();
        const newCardData = {
            id: newCardId,
            mk_id: courseId,
            title: courseName,
            kode_enroll: kodeEnroll,
        };

        // Send data to server
        @if(Route::has('kelas-praktikum.laboran.store'))
            fetch('{{ route("kelas-praktikum.laboran.store") }}', {
        @else
            console.warn('Route kelas-praktikum.laboran.store not defined');
        @endif
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Accept': 'application/json'
                },
                body: JSON.stringify({
                    mk_id: courseId,
                    kode_kelas: classCode,
                    kode_enroll: kodeEnroll,
                    status: 'aktif'
                })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    window.location.reload();
                } else {
                    // Tampilkan pesan error SQL jika ada
                    if (data.message && data.message.includes('SQLSTATE[HY000]')) {
                        showToast('Terjadi kesalahan server saat membuat kelas praktikum. Periksa data dan mk_id. ' + data.message, 'danger');
                    } else {
                        showToast(data.message || 'Gagal menambahkan kelas praktikum', 'danger');
                    }
                }
            })
            .catch(error => {
                // Tampilkan pesan error SQL jika ada
                if (error && error.message && error.message.includes('SQLSTATE[HY000]')) {
                    showToast('Terjadi kesalahan server saat membuat kelas praktikum. Pastikan data benar dan mk_id dikirimkan. ' + error.message, 'danger');
                } else {
                    console.error('Error:', error);
                    showToast('Terjadi kesalahan saat menambahkan kelas praktikum', 'danger');
                }
            });
    }

    document.addEventListener('click', function(event) {
        if (event.target.classList.contains('btn-edit')) {
            const cardId = event.target.dataset.cardId;
            const encodedCardData = event.target.dataset.cardData;

            const textarea = document.createElement('textarea');
            textarea.innerHTML = encodedCardData;
            const unescapedCardData = textarea.value;

            try {
                const cardData = JSON.parse(unescapedCardData);
                editCard(cardId, cardData);
            } catch (e) {
                console.error('Error parsing JSON for card:', cardId, e);
                console.error('Encoded data:', encodedCardData);
                console.error('Unescaped data:', unescapedCardData);
            }
        }
    });
</script>
@endpush