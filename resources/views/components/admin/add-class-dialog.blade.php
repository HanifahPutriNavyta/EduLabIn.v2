<x-base-dialog
    dialog-id="addClassDialog"
    title="Tambah Kelas Praktikum Baru"
    size="modal-dialog-centered">

    <form id="addClassForm">
        <div class="mb-3">
            <label for="newCourseName" class="form-label">Nama Mata Kuliah</label>
            <select class="form-select" id="newCourseName" required>
                <option value="">Pilih Mata Kuliah</option>
                @foreach($mataKuliahs as $mataKuliah)
                    <option value="{{ $mataKuliah->mk_id }}">{{ $mataKuliah->nama_mk }}</option>
                @endforeach
            </select>
        </div>
        <div class="mb-3">
            <label for="newClassCode" class="form-label">Kode Kelas</label>
            <input type="text" class="form-control" id="newClassCode" required>
        </div>
        <div class="mb-3">
            <label for="newKodeEnroll" class="form-label">Kode Enroll</label>
            <input type="text" class="form-control" id="newKodeEnroll" required>
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
