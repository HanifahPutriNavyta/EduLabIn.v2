<x-base-dialog
    dialog-id="editClassDialog"
    title="Pemograman Lanjut"
    size="modal-lg">

    <div class="mb-4">
        <div class="requirements-header">
            <h6><strong>Ketentuan:</strong></h6>
        </div>
        <div class="requirements-list">
            <p class="mb-1">• Min. Semester 4</p>
            <p class="mb-1">• Nilai Pemograman Dasar A</p>
            <p class="mb-0">• IPK Min. 3,5</p>
        </div>
        <div class="class-count-info mt-3">
            <p class="mb-0"><strong>Jumlah Kelas Praktikum: 20</strong></p>
        </div>
    </div>

    <hr class="my-3" />

    <form id="editClassForm">
        <div class="mb-3">
            <label for="courseName" class="form-label">Nama Mata Kuliah</label>
            <input type="text" class="form-control" id="courseName" placeholder="Masukkan Nama Mata Kuliah" disabled>
        </div>

        <div class="mb-3">
            <label for="classCode" class="form-label">Kode Kelas</label>
            <input type="text" class="form-control" id="classCode" required>
        </div>
        <div class="mb-3">
            <label for="kodeEnroll" class="form-label">Kode Enroll</label>
            <input type="text" class="form-control" id="kodeEnroll" required>
        </div>

    </form>

    <x-slot name="footer">
        <button type="button" class="btn btn-update" onclick="updateClass()">Update</button>
    </x-slot>
</x-base-dialog>

<style>
.requirements-info {
    background-color: #f8f9fa;
    border-radius: 10px;
    padding: 16px;
    margin-bottom: 20px;
}

.requirements-header h6 {
    font-family: 'Montserrat', sans-serif;
    font-size: 14px;
    font-weight: 600;
    color: #333;
    margin-bottom: 8px;
}

.requirements-list p {
    font-family: 'Montserrat', sans-serif;
    font-size: 14px;
    color: #333;
    margin-left: 8px;
}

.class-count-info p {
    font-family: 'Montserrat', sans-serif;
    font-size: 14px;
    font-weight: 600;
    color: #333;
}

.form-control,
.form-control:focus {
    box-shadow: none;
    border: 1px solid;
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
}

.btn-update:hover {
    background-color: var(--secondary-orange800);
    color: white;
}
</style>
