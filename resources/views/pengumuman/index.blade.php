@extends('layouts.app')
@section('title', 'Pengumuman - EduLabIn')
@push('styles')
<style>
    /* FILE UPLOAD STYLES */
    .upload-card {
        background-color: var(--white);
        border-radius: 10px;
        padding: 0px;
        box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        border: 1px solid var(--primary);
        overflow: hidden;
        max-width: 500px;
        margin: 1rem auto;
    }

    .upload-card .card-header {
        background-color: var(--secondary);
        color: var(--black);
        padding: 12px 20px;
        padding-left: 12px;
        border-radius: 0;
        font-weight: 600;
        border-bottom: 1px solid var(--primary);
        text-align: left;
    }

    .file-upload-label {
        display: flex;
        flex-direction: column;
        align-items: center;
        cursor: pointer;
        width: 100%;
    }

    .icon-upload {
        width: 34px;
        height: 34px;
        vertical-align: middle;
        margin: 10px 0px;
    }

    .file-upload-input {
        position: absolute;
        opacity: 0;
        width: 0.1px;
        height: 0.1px;
    }

    .file-info {
        margin-top: 8px;
        font-size: 0.875rem;
        color: var(--neutral-600);
    }
</style>
@endpush

@section('content')

<h1 class="page-title">Pengumuman</h1>

<div class="search-container mb-3 position-relative">
    <input type="text" class="form-control search-input" placeholder="Cari Judul Pengumuman..." aria-label="Search">
    <i class="bi bi-search search-icon position-absolute top-50 end-0 translate-middle-y me-3"></i>
</div>

<hr class="my-4">

<div id="announcementCardsContainer">

    @foreach($pengumumans as $announcement)
    <x-admin.announcement-card
        :card-id="$announcement->pengumuman_id"
        :title="$announcement->judul"
        :description="$announcement->deskripsi"
        :data="$announcement" />
    @endforeach
</div>

<!-- Template for new cards -->
<template id="announcementCardTemplate">
    @php
    $templateData = [
    'id' => 'TEMPLATE_ID',
    'title' => 'TEMPLATE_TITLE',
    'description' => 'TEMPLATE_DESCRIPTION',
    'data' => [
    'id' => 'TEMPLATE_ID',
    'title' => 'TEMPLATE_TITLE',
    'description' => 'TEMPLATE_DESCRIPTION',
    ]
    ];
    @endphp
    <x-admin.announcement-card
        :card-id="$templateData['id']"
        :title="$templateData['title']"
        :data="$templateData['data']" />
</template>

<button class="fab-button" onclick="openAddAnnouncementDialog()">
    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="#222" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
        <line x1="12" y1="5" x2="12" y2="19"></line>
        <line x1="5" y1="12" x2="19" y2="12"></line>
    </svg>
</button>

<x-admin.edit-announcement-dialog />
<x-admin.add-announcement-dialog />

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

    function removeCard(cardId) {
        if (confirm('Apakah Anda yakin ingin menghapus pengumuman ini?')) {
            fetch(`{{ route('pengumuman.laboran.destroy', ['pengumuman' => ':pengumuman']) }}`.replace(':pengumuman', cardId), {
                    method: 'DELETE',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                        'Content-Type': 'application/json',
                    }
                })
                .then(response => {
                    if (response.ok) {
                        const card = document.querySelector(`[data-card-id="${cardId}"]`);
                        if (card) {
                            card.style.transition = 'opacity 0.3s ease-out, transform 0.3s ease-out';
                            card.style.opacity = '0';
                            card.style.transform = 'translateX(100%)';

                            setTimeout(() => {
                                card.remove();
                            }, 300);
                        }
                    } else {
                        showToast('Failed to delete announcement', 'error');
                    }
                })
                .catch(error => {
                    console.error('Error deleting announcement:', error);
                    showToast('Error deleting announcement', 'error');
                });
        }
    }

    function editCard(cardId, cardData) {
        currentEditingCard = {
            id: cardId,
            data: cardData
        };
        console.log(currentEditingCard);

        document.querySelector('#editAnnouncementDialog .dialog-title').textContent = cardData.judul;

        document.getElementById('announcementTitle').value = cardData.judul;
        document.getElementById('announcementDescription').value = cardData.deskripsi || '';

        const modal = new bootstrap.Modal(document.getElementById('editAnnouncementDialog'));
        modal.show();
    }

    function updateAnnouncement() {
        if (!currentEditingCard) return;

        const announcementTitle = document.getElementById('announcementTitle').value;
        const announcementDescription = document.getElementById('announcementDescription').value;
        const announcementImageInput = document.getElementById('edit-foto-announcement');
        const announcementImage = announcementImageInput ? announcementImageInput.files[0] : null;

        const formData = new FormData();
        formData.append('judul', announcementTitle);
        formData.append('deskripsi', announcementDescription);
        if (announcementImage) {
            formData.append('gambar', announcementImage);
        }
        formData.append('_method', 'PUT');

        fetch(`{{ route('pengumuman.laboran.update', ['pengumuman' => ':pengumuman']) }}`.replace(':pengumuman', currentEditingCard.id), {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                    'Accept': 'application/json',
                },
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    const card = document.querySelector(`[data-card-id="${currentEditingCard.id}"]`);
                    if (card) {
                        card.querySelector('.card-title').textContent = announcementTitle;

                        const descriptionElement = card.querySelector('.card-description');
                        if (descriptionElement) {
                            descriptionElement.textContent = announcementDescription;
                        }

                        const updatedData = {
                            ...currentEditingCard.data,
                            judul: announcementTitle,
                            deskripsi: announcementDescription
                        };

                        const editButton = card.querySelector('.btn-edit');
                        if (editButton) {
                            editButton.setAttribute('data-card-data', JSON.stringify(updatedData));
                        }
                    }

                    const modal = bootstrap.Modal.getInstance(document.getElementById('editAnnouncementDialog'));
                    modal.hide();

                    currentEditingCard = null;
                } else {
                    showToast('Failed to update announcement', 'error');
                }
            })
            .catch(error => {
                console.error('Error updating announcement:', error);
                showToast('Error updating announcement', 'error');
            });
    }

    function openAddAnnouncementDialog() {
        document.getElementById('addAnnouncementForm').reset();
        const modal = new bootstrap.Modal(document.getElementById('addAnnouncementDialog'));
        modal.show();
    }

    function addNewAnnouncement() {
        const announcementTitle = document.getElementById('newAnnouncementTitle').value;
        const announcementDescription = document.getElementById('newDescription').value;

        if (!announcementTitle.trim() || !announcementDescription.trim()) {
            showToast('Please fill in all fields', 'error');
            return;
        }

        const formData = new FormData();
        formData.append('judul', document.getElementById('newAnnouncementTitle').value);
        formData.append('deskripsi', document.getElementById('newDescription').value);
        const announcementImage = document.getElementById('foto-announcement').files[0];
        if (announcementImage) {
            formData.append('gambar', announcementImage);
        }
        const newStatus = document.getElementById('newAnnouncementStatus') ? document.getElementById('newAnnouncementStatus').value : '0';
        formData.append('status', newStatus);
    
        
        fetch(`{{ route('pengumuman.laboran.store') }}`, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                     'Accept': 'application/json',
                },
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    const newAnnouncement = data.pengumuman;
                    const newCardId = newAnnouncement.pengumuman_id;
                    const newCardData = {
                        id: newCardId,
                        judul: announcementTitle,
                        deskripsi: announcementDescription,
                        status: typeof newAnnouncement.status !== 'undefined' ? (newAnnouncement.status ? 1 : 0) : (parseInt(newStatus) || 0)
                    };

                    const template = document.getElementById('announcementCardTemplate');
                    const cardContainer = document.getElementById('announcementCardsContainer');
                    const newCard = template.content.cloneNode(true);
                    const cardElement = newCard.querySelector('.announcement-card');

                    cardElement.setAttribute('data-card-id', newCardId);
                    cardElement.querySelector('.card-title').textContent = announcementTitle;

                    const editButton = cardElement.querySelector('.btn-edit');
                    editButton.setAttribute('data-card-id', newCardId);
                    editButton.setAttribute('data-card-data', JSON.stringify(newCardData));

                    const closeButton = cardElement.querySelector('.btn-close');
                    closeButton.setAttribute('onclick', `removeCard('${newCardId}')`);

                    // Initialize status badge(s) and toggle button on the newly created card
                    const s = parseInt(newCardData.status || 0, 10);
                    const badges = cardElement.querySelectorAll('.status-badge');
                    badges.forEach(badge => {
                        badge.textContent = s ? 'Aktif' : 'Nonaktif';
                        badge.classList.remove('status-active', 'status-inactive');
                        badge.classList.add(s ? 'status-active' : 'status-inactive');
                    });
                    const toggleBtn = cardElement.querySelector('.btn-toggle-status');
                    if (toggleBtn) {
                        toggleBtn.dataset.pengumumanId = newCardId;
                        toggleBtn.dataset.currentStatus = s ? '1' : '0';
                        toggleBtn.textContent = s ? 'Nonaktifkan' : 'Aktifkan';
                    }

                    cardContainer.appendChild(newCard);

                    requestAnimationFrame(() => {
                        cardElement.style.transition = 'opacity 0.3s ease-out, transform 0.3s ease-out';
                        cardElement.style.opacity = '1';
                        cardElement.style.transform = 'translateX(0)';
                    });

                    const modal = bootstrap.Modal.getInstance(document.getElementById('addAnnouncementDialog'));
                    modal.hide();
                } else {
                    showToast('Failed to create announcement', 'error');
                }
            })
            .catch(error => {
                console.error('Error creating announcement:', error);
                showToast('Error creating announcement', 'error');
            });
    }

    // IMPROVED EVENT LISTENER FOR TOGGLE STATUS AND EDIT
    document.addEventListener('click', function(event) {
        // Logika untuk tombol Edit
        if (event.target.classList.contains('btn-edit')) {
            const cardId = event.target.dataset.cardId;
            const encodedCardData = event.target.dataset.cardData;

            try {
                const cardData = JSON.parse(encodedCardData);
                editCard(cardId, cardData);
            } catch (e) {
                console.error('Error parsing JSON for card:', cardId, e);
                console.error('Encoded data:', encodedCardData);
            }
        }
        
        // Logika untuk tombol Toggle Status - IMPROVED VERSION
        const btn = event.target.closest('.btn-toggle-status');
        if (btn) {
            const id = btn.dataset.pengumumanId;
            const current = btn.dataset.currentStatus === '1' ? 1 : 0;
            const next = current === 1 ? 0 : 1;

            // Disable button during request to prevent double clicks
            btn.disabled = true;
            const originalText = btn.textContent;

            fetch(`{{ url('pengumuman/toggle-status') }}` + `/${id}`, {
                method: 'PUT',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                    'Accept': 'application/json'
                },
                body: JSON.stringify({ status: next })
            })
            .then(response => {
                if (response.ok) {
                    return response.text().then(text => {
                        if (!text) return { status: response.status, data: null };
                        try {
                            return { status: response.status, data: JSON.parse(text) };
                        } catch (e) {
                            return { status: response.status, data: null };
                        }
                    });
                }
                return response.text().then(text => {
                    let msg = text;
                    try { 
                        const parsed = JSON.parse(text); 
                        if (parsed && parsed.message) msg = parsed.message; 
                    } catch (e) {}
                    return Promise.reject({ status: response.status, message: msg });
                });
            })
            .then(result => {
                const data = result.data;
                const isSuccess = (data && data.success) || (result.status >= 200 && result.status < 300 && !data) || result.status === 204;

                if (isSuccess) {
                    const card = document.querySelector(`[data-card-id="${id}"]`);
                    if (card) {
                        // Update all status badges in the card
                        const badges = card.querySelectorAll('.status-badge');
                        badges.forEach(badge => {
                            badge.textContent = next ? 'Aktif' : 'Nonaktif';
                            badge.classList.remove('status-active', 'status-inactive');
                            badge.classList.add(next ? 'status-active' : 'status-inactive');
                        });

                        // Update button state and text
                        btn.dataset.currentStatus = next.toString();
                        btn.textContent = next === 1 ? 'Nonaktifkan' : 'Aktifkan';

                        // Update edit button data
                        const editBtn = card.querySelector('.btn-edit');
                        if (editBtn) {
                            try {
                                let raw = editBtn.getAttribute('data-card-data') || editBtn.dataset.cardData || '{}';
                                // Decode HTML entities if needed
                                const ta = document.createElement('textarea');
                                ta.innerHTML = raw;
                                raw = ta.value;
                                const parsed = JSON.parse(raw);
                                parsed.status = next;
                                editBtn.setAttribute('data-card-data', JSON.stringify(parsed));
                            } catch (e) {
                                console.warn('Failed to update edit button data-card-data', e);
                            }
                        }

                        // Update search functionality (re-index the card content)
                        const searchInput = document.querySelector('.search-input');
                        if (searchInput && searchInput.value.trim()) {
                            // Re-trigger search to update visibility if needed
                            searchInput.dispatchEvent(new Event('input'));
                        }
                    }
                    
                    showToast('Status pengumuman diperbarui', 'success');
                } else {
                    // Revert button text on failure
                    btn.textContent = originalText;
                    showToast((data && data.message) || 'Gagal memperbarui status', 'error');
                }
            })
            .catch(err => {
                console.error('Toggle status error:', err);
                // Revert button text on error
                btn.textContent = originalText;
                const msg = (err && err.message) ? err.message : 'Terjadi kesalahan saat memperbarui status';
                showToast(msg, 'error');
            })
            .finally(() => {
                // Re-enable button
                btn.disabled = false;
            });
        }
    });

    // IMPROVED DOM CONTENT LOADED
    document.addEventListener('DOMContentLoaded', function() {
        // Search functionality
        const searchInput = document.querySelector('.search-input');
        const cardsContainer = document.getElementById('announcementCardsContainer');

        if (searchInput && cardsContainer) {
            searchInput.addEventListener('input', function() {
                const searchTerm = this.value.toLowerCase();
                let hasVisibleCards = false;
                
                // Re-query cards each time to include dynamically added ones
                const cards = cardsContainer.querySelectorAll('.announcement-card');
                
                cards.forEach(card => {
                    const text = card.textContent.toLowerCase();
                    if (text.includes(searchTerm)) {
                        card.style.display = 'block';
                        hasVisibleCards = true;
                    } else {
                        card.style.display = 'none';
                    }
                });
            });
        }

        // File upload validation
        const fotoInput = document.getElementById('foto-announcement');
        const maxSize = 2 * 1024 * 1024; // 2MB
        const fotoInputEdit = document.getElementById('edit-foto-announcement');

        if (fotoInput) {
            fotoInput.addEventListener('change', function(e) {
                validateFile(e.target, 'foto-info');
            });
        }

        if (fotoInputEdit) {
            fotoInputEdit.addEventListener('change', function(e) {
                validateFile(e.target, 'foto-info-edit');
            });
        }

        function validateFile(input, infoId) {
            const file = input.files[0];
            const infoElement = document.getElementById(infoId);

            if (!file) {
                if (infoElement) infoElement.textContent = '';
                return;
            }

            if (file.size > maxSize) {
                infoElement.textContent = 'File terlalu besar (max 2MB)';
                infoElement.style.color = 'var(--error)';
                infoElement.style.textAlign = 'center'; 
                input.value = '';
            } else {
                infoElement.textContent = `File dipilih: ${file.name} (${formatFileSize(file.size)})`;
                infoElement.style.color = 'var(--success)';
                infoElement.style.textAlign = 'center'; 
            }
        }

        function formatFileSize(bytes) {
            if (bytes < 1024) return bytes + ' bytes';
            else if (bytes < 1048576) return (bytes / 1024).toFixed(1) + ' KB';
            else return (bytes / 1048576).toFixed(1) + ' MB';
        }
    });
</script>
@endpush