@extends('layouts.app')
@section('title', 'Mata Kuliah - EduLabIn')

@section('content')
<main class="container py-4">
    <h1 class="page-title">Pendaftaran Kelas Praktikum</h1>

    <div class="search-container mb-3 position-relative">
        <input type="text" class="form-control search-input" placeholder="Cari Mata Kuliah..." aria-label="Search">
        <i class="bi bi-search search-icon position-absolute top-50 end-0 translate-middle-y me-3"></i>
    </div>

    <hr class="my-4">

    <div id="classCardsContainer">
        @foreach($mataKuliahs as $mataKuliah)
            <x-admin.matakuliah-card
                :card-id="$mataKuliah->mk_id"
                :title="$mataKuliah->nama_mk"
                :requirements="optional($mataKuliah->pendaftaranAspraks)->ketentuan ?? ''"
                :class-count="optional($mataKuliah->pendaftaranAspraks)->kuota ?? 0"
                :data="[
                    'mk_id' => $mataKuliah->mk_id,
                    'nama_mk' => $mataKuliah->nama_mk,
                    'ketentuan' => optional($mataKuliah->pendaftaranAspraks)->ketentuan ?? '',
                    'kapasitas' => optional($mataKuliah->pendaftaranAspraks)->kuota ?? 0,
                    'status' => (int) (optional($mataKuliah->pendaftaranAspraks)->status_pendaftaran ?? 0),
                ]"
            />
        @endforeach
    </div>
    
    <div id="noResultsMessage" class="text-center mt-4" style="display: none;">
        <p class="text-muted">Tidak ada kelas praktikum yang ditemukan untuk mata kuliah ini.</p>       
    </div>
    <!-- Template for new cards -->
    <template id="classCardTemplate">
        @php
            $templateData = [
                'mk_id' => 'TEMPLATE_ID',
                'nama_mk' => 'TEMPLATE_TITLE',
                'ketentuan' => 'TEMPLATE_REQUIREMENT',
                'kapasitas' => 0,
            ];
        @endphp
        <x-admin.matakuliah-card
            :card-id="$templateData['mk_id']"
            :title="$templateData['nama_mk']"
            :requirements="$templateData['ketentuan']"
            :class-count="$templateData['kapasitas']"
            :data="$templateData"
        />
    </template>

    <button class="fab-button" onclick="openAddClassDialog()">
        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="#222" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
            <line x1="12" y1="5" x2="12" y2="19"></line>
            <line x1="5" y1="12" x2="19" y2="12"></line>
        </svg>
    </button>

    <x-admin.edit-matakuliah-dialog />

    <x-admin.add-matakuliah-dialog />

    <!-- Delete Confirmation Modal -->
    <div class="modal fade" id="deleteConfirmModal" tabindex="-1" aria-labelledby="deleteConfirmModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="deleteConfirmModalLabel">Konfirmasi Hapus</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <p>Apakah Anda yakin ingin menghapus mata kuliah ini?</p>
                    <p class="text-muted" id="deleteClassName"></p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="button" class="btn btn-danger" onclick="confirmDelete()">Hapus</button>
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
        z-index: 1000;
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
let currentEditingCard = null;
let cardToDelete = null;

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
    if (typeof cardData === 'string') {
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
                mk_id: cardId,
                nama_mk: 'Kelas Praktikum'
            };
        }
    }

    // Show class name in confirmation modal
    const className = parsedCardData.nama_mk || parsedCardData.title || 'Kelas Praktikum';
    document.getElementById('deleteClassName').textContent = className;

    // Show confirmation modal
    const modal = bootstrap.Modal.getOrCreateInstance(document.getElementById('deleteConfirmModal'));
    modal.show();
}

function confirmDelete() {
    if (!cardToDelete) return;

    const card = document.querySelector(`[data-card-id="${cardToDelete.id}"]`);
    if (card) {
        // Send delete request to server
        fetch(`{{ route("matakuliah.laboran.destroy", ["matakuliah" => ":id"]) }}`.replace(':id', cardToDelete.id), {
            method: 'DELETE',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Accept': 'application/json'
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                // Animate card removal
                card.style.transition = 'opacity 0.3s ease-out, transform 0.3s ease-out';
                card.style.opacity = '0';
                card.style.transform = 'translateX(100%)';

                setTimeout(() => {
                    card.remove();
                }, 300);

                // Close modal first, cleanup, then notify
                const inst = bootstrap.Modal.getInstance(document.getElementById('deleteConfirmModal'));
                if (inst) inst.hide();
                cleanupModalArtifacts();
                showToast('Mata kuliah berhasil dihapus', 'success', { reload: true });
            } else {
                showToast(data.message || 'Gagal menghapus mata kuliah', 'danger');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            showToast('Terjadi kesalahan saat menghapus mata kuliah', 'danger');
        })
        .finally(() => {
            // Close modal and reset
            const modal = bootstrap.Modal.getInstance(document.getElementById('deleteConfirmModal'));
            if (modal) modal.hide();
            cleanupModalArtifacts();
            cardToDelete = null;
        });
    }
}

function editCard(cardId, cardData) {
    currentEditingCard = {
        id: cardId,
        data: cardData
    };

    // Set values in edit dialog
    document.querySelector('#editClassDialog .dialog-title').textContent = cardData.nama_mk;
    const requirementsList = document.querySelector('#editClassDialog .requirements-list');
    requirementsList.innerHTML = (cardData.ketentuan || '')
        .split('\n')
        .map(line => `<p class="mb-1 requirement-item">${line.trim()}</p>`)
        .join('');
    document.querySelector('#editClassDialog .class-count-info p').textContent = `Jumlah Kelas Praktikum: ${cardData.kapasitas ?? 0}`;

    document.getElementById('courseName').value = cardData.nama_mk;
    document.getElementById('requirements').value = cardData.ketentuan || '';
    document.getElementById('classCount').value = cardData.kapasitas ?? 0;

    const modal = bootstrap.Modal.getOrCreateInstance(document.getElementById('editClassDialog'));
    modal.show();
}

function updateClass() {
    if (!currentEditingCard) return;

    const courseName = document.getElementById('courseName').value;
    const requirementsText = document.getElementById('requirements').value;
    const classCount = document.getElementById('classCount').value;

    // Validation
    if (!courseName.trim()) return showToast('Nama mata kuliah tidak boleh kosong', 'warning');
    if (!requirementsText.trim()) return showToast('Ketentuan tidak boleh kosong', 'warning');
    if (!classCount || classCount <= 0) return showToast('Jumlah kelas harus lebih dari 0', 'warning');

    // Split requirements by line and trim
    const requirementsLines = requirementsText.split('\n').map(line => line.replace(/^•\s*/, '').trim()).filter(Boolean);

    // Prepare data for server
    const updateData = {
        mk_id: currentEditingCard.data.mk_id,
        nama_mk: courseName,
        ketentuan: requirementsLines.join('\n'),
        kapasitas: parseInt(classCount),
        status: 'aktif'
    };

    // Send update request to server
    fetch(`{{ route('matakuliah.laboran.update', ['matakuliah' => ':id']) }}`.replace(':id', currentEditingCard.data.mk_id), {
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
        if (!data.success) return showToast(data.message || 'Gagal memperbarui mata kuliah', 'danger');

        // Update the card in DOM
        const card = document.querySelector(`[data-card-id="${currentEditingCard.id}"]`);
        if (card) {
            card.querySelector('.card-title').textContent = courseName;
            const requirementsList = card.querySelector('.card-requirements');
            requirementsList.innerHTML = `<p class=\"mb-1\"><strong>Ketentuan:</strong></p>` +
                requirementsLines.map(line => `<p class=\"requirement-item mb-1\">${line}</p>`).join('');
            card.querySelector('.class-count').innerHTML = `<strong>Jumlah Kelas Praktikum: ${classCount}</strong>`;
            const updatedData = {
                ...currentEditingCard.data,
                nama_mk: courseName,
                kapasitas: parseInt(classCount),
                ketentuan: requirementsLines.join('\\n')
            };
            card.querySelector('.btn-edit').setAttribute('data-card-data', JSON.stringify(updatedData));
        }

        // Hide modal first, then cleanup and notify
        const modal = bootstrap.Modal.getInstance(document.getElementById('editClassDialog'));
        if (modal) modal.hide();
        cleanupModalArtifacts();
    showToast('Mata kuliah berhasil diperbarui', 'success', { reload: true });
    })
    .catch(error => { console.error('Error:', error); showToast('Terjadi kesalahan saat memperbarui mata kuliah', 'danger'); })
    .finally(() => {
        currentEditingCard = null;
    });
}

function openAddClassDialog() {
    const form = document.getElementById('addClassForm');
    if (form) form.reset();
    const modal = bootstrap.Modal.getOrCreateInstance(document.getElementById('addClassDialog'));
    modal.show();
}

function addNewClass() {
    const courseName = document.getElementById('newCourseName').value.trim();
    const requirementsText = document.getElementById('newRequirements').value;
    const classCount = document.getElementById('newClassCount').value;

    // Validation
    if (!courseName) return showToast('Nama mata kuliah tidak boleh kosong', 'warning');
    if (!requirementsText.trim()) return showToast('Ketentuan tidak boleh kosong', 'warning');
    if (!classCount || classCount <= 0) return showToast('Jumlah kelas harus lebih dari 0', 'warning');

    // Split requirements by line and trim
    const requirementsLines = requirementsText.split('\n').map(line => line.replace(/^•\s*/, '').trim()).filter(Boolean);

    // Generate a unique ID for the new card (could use timestamp or random string)
    const newCardId = 'mk_' + Date.now();

    const newCardData = {
        nama_mk: courseName,
        ketentuan: requirementsLines.join('\n'),
        kapasitas: parseInt(classCount)
    };

    // Send data to server
    fetch('{{ route("matakuliah.laboran.store") }}', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': '{{ csrf_token() }}',
            'Accept': 'application/json'
        },
        body: JSON.stringify({
            nama_mk: courseName,
            kapasitas: parseInt(classCount),
            status: 'aktif',
            ketentuan: requirementsLines.join('\n')
        })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            // Create new card with server response data
            const template = document.getElementById('classCardTemplate');
            const cardContainer = document.getElementById('classCardsContainer');
            const newCard = template.content.cloneNode(true);
            const cardElement = newCard.querySelector('.class-card');

            cardElement.setAttribute('data-card-id', newCardId);
            cardElement.querySelector('.card-title').textContent = courseName;

            const requirementsList = cardElement.querySelector('.card-requirements');
            requirementsList.innerHTML = `<p class="mb-1"><strong>Ketentuan:</strong></p>` +
                requirementsLines.map(line => `<p class="requirement-item mb-1">${line}</p>`).join('');

            cardElement.querySelector('.class-count').innerHTML = `<strong>Jumlah Kelas Praktikum: ${classCount}</strong>`;

            const editButton = cardElement.querySelector('.btn-edit');
            editButton.setAttribute('data-card-id', newCardId);
            editButton.setAttribute('data-card-data', JSON.stringify(newCardData));

            const closeButton = cardElement.querySelector('.btn-close');
            closeButton.setAttribute('onclick', `removeCard('${newCardId}', ${JSON.stringify(newCardData)})`);

            cardElement.style.opacity = '0';
            cardElement.style.transform = 'translateX(100%)';
            cardContainer.appendChild(newCard);

            requestAnimationFrame(() => {
                cardElement.style.transition = 'opacity 0.3s ease-out, transform 0.3s ease-out';
                cardElement.style.opacity = '1';
                cardElement.style.transform = 'translateX(0)';
            });

            const modal = bootstrap.Modal.getInstance(document.getElementById('addClassDialog'));
            if (modal) modal.hide();
            cleanupModalArtifacts();
            showToast('Mata kuliah berhasil ditambahkan', 'success', { reload: true });
        } else {
            showToast(data.message || 'Gagal menambahkan mata kuliah', 'danger');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        showToast('Terjadi kesalahan saat menambahkan mata kuliah', 'danger');
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
    if (event.target.classList.contains('btn-toggle-status')) {
        const btn = event.target;
        const mkId = btn.dataset.mkId;
        const current = (btn.dataset.currentStatus || 'nonaktif').toLowerCase();
        const next = current === 'aktif' ? 'nonaktif' : 'aktif';
        // Build payload according to controller expectations
        fetch(`{{ route('matakuliah.laboran.toggleStatus', ['matakuliah' => ':id']) }}`.replace(':id', mkId), {
            method: 'PUT',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Accept': 'application/json'
            },
            body: JSON.stringify({ status: next })
        })
        .then(r => r.json())
        .then(data => {
            if (data && data.success) {
                // Update UI badge/button
                const wrapper = btn.closest('.action-group');
                const badge = wrapper.querySelector('.status-badge');
                const isActive = next === 'aktif';
                badge.textContent = isActive ? 'Aktif' : 'Nonaktif';
                badge.classList.toggle('bg-success', isActive);
                badge.classList.toggle('bg-danger', !isActive);
                btn.textContent = isActive ? 'Nonaktifkan' : 'Aktifkan';
                btn.dataset.currentStatus = next;
                showToast('Status pendaftaran berhasil diubah', 'success', { reload: true });
            } else {
                showToast((data && data.message) || 'Gagal mengubah status', 'danger');
            }
        })
        .catch(err => { console.error(err); showToast('Terjadi kesalahan saat mengubah status', 'danger'); });
    }
});

// Minimal toast helper for status-only notifications
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
// Ensure no lingering Bootstrap artifacts after modals close
function cleanupModalArtifacts(){
    document.body.classList.remove('modal-open');
    document.body.style.removeProperty('padding-right');
    document.querySelectorAll('.modal-backdrop').forEach(b => b.remove());
}

// Avoid global hidden cleanup to prevent race conditions with a new backdrop
</script>
@endpush
