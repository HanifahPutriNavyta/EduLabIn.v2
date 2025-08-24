<x-base-dialog
    dialog-id="addClassDialog"
    title="Tambah Kelas Praktikum Baru"
    size="modal-dialog-centered">

    <form id="addClassForm">
        <div class="mb-3">
            <label for="newCourseName" class="form-label">Nama Mata Kuliah</label>
            <input type="text" class="form-control" id="newCourseName" placeholder="Masukkan Nama Mata Kuliah" required>
        </div>
      
        <div class="mb-3">
            <label for="newRequirements" class="form-label">Ketentuan</label>
            <textarea class="form-control" id="newRequirements" rows="4" required></textarea>
        </div>
        <div class="mb-3">
            <label for="newClassCount" class="form-label">Jumlah Kelas Praktikum</label>
            <input type="number" class="form-control" id="newClassCount" min="1" required>
        </div>
    </form>

    <x-slot name="footer">
        <button type="button" class="btn btn-update" onclick="addNewClass()">Tambah</button>
    </x-slot>
</x-base-dialog>

<style>
.form-label {
    font-family: 'Montserrat', sans-serif;
    font-size: 14px;
    font-weight: 500;
    color: #333;
    margin-bottom: 8px;
}

.form-control,
.form-control:focus,
.form-select,
.form-select:focus {
    box-shadow: none;
    border: 1px solid #ddd;
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
}

.btn-update:hover {
    background-color: var(--secondary-orange800);
    color: white;
}
</style>
