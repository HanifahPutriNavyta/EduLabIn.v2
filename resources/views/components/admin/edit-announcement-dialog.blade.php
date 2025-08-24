<x-base-dialog
    dialog-id="editAnnouncementDialog"
    title="Pembukaan Rekruitmen Untuk Semester Genap Tahun Ajaran 2025/2026"
    size="modal-lg">
    
    <div class="dotted-line"></div>

    <hr class="my-3" />

    <form id="editAnnouncementForm">
        <div class="mb-3">
            <label for="announcementTitle" class="form-label">Judul Pengumuman</label>
            <input type="text" class="form-control" id="announcementTitle" placeholder="Masukkan Judul Pengumuman">
        </div>

        <div class="mb-3">
            <label for="announcementDescription" class="form-label">Deskripsi Pengumuman</label>
            <textarea class="form-control" id="announcementDescription" rows="8" placeholder="Masukkan Deskripsi Pengumuman"></textarea>
        </div>
        <div class="mb-3">
            <small class="text-info">info jika tidak ada gambar yang diupload, maka gambar lama akan tetap digunakan</small>
        </div>
        <div class="mb-3">
            <div class="upload-card">
                <div class="card-header">Upload Gambar</div>
                <div class="card-body">
                    <div class="card-detail">
                        <label for="foto-announcement" class="file-upload-label">
                            <img src="{{ asset('img/IconUpload.png') }}" alt="icon-upload" class="icon-upload">
                            <input type="file" class="form-control file-upload-input" id="edit-foto-announcement" name="gambar" accept=".jpg, .jpeg, .png, .gif">
                        </label>
                        <div class="file-info" id="foto-info-edit"></div>
                    </div>
                </div>
            </div>
        </div>

    </form>

    <x-slot name="footer">
        <button type="button" class="btn btn-update" onclick="updateAnnouncement()">Update</button>
    </x-slot>
</x-base-dialog>

<style>
.dotted-line {
    border-top: 3px dotted #666;
    width: 100%;
    margin: 8px 0;
}

.form-control,
.form-control:focus {
    box-shadow: none;
    border: 1px solid #ddd;
    border-radius: 8px;
    padding: 12px;
    font-family: 'Montserrat', sans-serif;
    font-size: 14px;
}

.form-label {
    font-family: 'Montserrat', sans-serif;
    font-size: 14px;
    font-weight: 500;
    color: #333;
    margin-bottom: 8px;
}

.btn-update {
    background-color: var(--secondary);
    color: white;
    border: none;
    border-radius: 8px;
    padding: 10px 24px;
    font-family: 'Montserrat', sans-serif;
    font-size: 14px;
    font-weight: 500;
    min-width: 80px;
    transition: background-color 0.2s ease;
}

.btn-update:hover {
    background-color: var(--secondary-orange800);
    color: white;
}

textarea.form-control {
    resize: vertical;
    min-height: 120px;
}

textarea.form-control::placeholder {
    color: #999;
    font-style: italic;
}
</style>