@props(['data' => [], 'columns' => []])

<div class="table-responsive">
    <table class="table">
        <thead>
            <tr>
                @foreach($columns as $column)
                    <th>{{ $column['label'] }}</th>
                @endforeach
            </tr>
        </thead>
        <tbody>
            @foreach($data as $row)
                <tr @if(isset($rowClass)) class="{{ $rowClass }}" @endif @if(isset($rowMkId)) data-mk-id="{{ $row[$rowMkId] ?? '' }}" @endif>
                    @foreach($columns as $column)
                        @if($column['key'] == 'foto')
                            <td>
                                @if(!empty($row[$column['key']]))
                                    <img src="{{ $row[$column['key']] }}" alt="Foto" style="width: 100px; height: 100px;">
                                @endif
                            </td>
                        @elseif($column['key'] == 'bukti')
                            <td>
                                @if(!empty($row[$column['key']]))
                                    @php
                                        $fileUrl = $row[$column['key']];
                                        $fileName = pathinfo($fileUrl, PATHINFO_BASENAME);
                                        $isPdf = strtolower(pathinfo($fileUrl, PATHINFO_EXTENSION)) === 'pdf';
                                    @endphp
                                    <a 
                                        href="{{ $fileUrl }}" 
                                        @if($isPdf)
                                            data-bs-toggle="modal"
                                            data-bs-target="#pdfModal"
                                            data-pertemuan="{{ $fileName }}"
                                            data-file="{{ $fileUrl }}"
                                            data-download="{{ $fileUrl }}"
                                            class="preview-bukti-link"
                                            style="cursor:pointer"
                                        @else
                                            target="_blank"
                                        @endif
                                    >
                                        Lihat Bukti
                                    </a>
                                @endif
                            </td>
                        @else
                            <td>{{ $row[$column['key']] ?? '' }}</td>
                        @endif
                    @endforeach
                </tr>
            @endforeach

            @php
                $emptyRows = max(0, 30 - count($data));
            @endphp

            @for($i = 0; $i < $emptyRows; $i++)
                <tr>
                    @foreach($columns as $column)
                        <td></td>
                    @endforeach
                </tr>
            @endfor
        </tbody>
    </table>
</div>

<!-- Modal PDF Preview (copied/adapted from BeritaAcaraDosen) -->
<div class="modal fade justify-content-center" id="pdfModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalJudulText">File Bukti</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
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
                <a href="#" class="btn btn-warning" id="downloadPdfBtn" target="_blank" rel="noopener">
                    <i class="bi bi-download"></i> Download
                </a>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const pdfModal = document.getElementById('pdfModal');
        const pdfPreviewFrame = document.getElementById('pdfPreviewFrame');
        const downloadBtn = document.getElementById('downloadPdfBtn');
        const modalJudulText = document.getElementById('modalJudulText');
        const modalPertemuanText = document.getElementById('modalPertemuanText');
        const noPreview = document.getElementById('noPreview');

        if (pdfModal) {
            pdfModal.addEventListener('show.bs.modal', function(event) {
                const button = event.relatedTarget;
                const judul = button.getAttribute('data-pertemuan');
                const fileUrl = button.getAttribute('data-file');
                const downloadUrl = button.getAttribute('data-download');

                // Set modal title and text
                modalJudulText.textContent = judul ? `File Bukti: ${judul}` : 'File Bukti';
                modalPertemuanText.textContent = judul ? `File Bukti: ${judul}` : '';

                // Set download link
                downloadBtn.href = downloadUrl || '#';

                // Only preview if file is PDF
                if (fileUrl && fileUrl.toLowerCase().endsWith('.pdf')) {
                    pdfPreviewFrame.src = fileUrl;
                    pdfPreviewFrame.style.display = '';
                    noPreview.style.display = 'none';
                } else if (fileUrl) {
                    pdfPreviewFrame.src = '';
                    pdfPreviewFrame.style.display = 'none';
                    noPreview.style.display = '';
                } else {
                    pdfPreviewFrame.src = '';
                    pdfPreviewFrame.style.display = 'none';
                    noPreview.style.display = '';
                }
            });

            // Clear iframe src on modal close to release memory
            pdfModal.addEventListener('hidden.bs.modal', function() {
                pdfPreviewFrame.src = '';
            });
        }
    });
</script>
@endpush

<style>
.table-responsive {
    overflow-x: auto;
    -webkit-overflow-scrolling: touch;
    margin: 0 -1rem;
    padding: 0 1rem;
}

.table {
    width: 100%;
    margin-bottom: 1rem;
    border-collapse: collapse;
    min-width: 800px;
}

.table th {
    background-color: var(--secondary);
    color: white;
    font-family: 'Montserrat', sans-serif;
    font-size: 14px;
    font-weight: 600;
    padding: 12px 16px;
    text-align: left;
    white-space: nowrap;
    border: 1px solid #ddd;
}

.table td {
    font-family: 'Montserrat', sans-serif;
    font-size: 14px;
    color: #333;
    padding: 12px 16px;
    border: 1px solid #ddd;
    white-space: nowrap;
}

.table tbody tr:nth-child(even) {
    background-color: #f9f9f9;
}

.table tbody tr:hover {
    background-color: #f5f5f5;
}

/* Custom scrollbar for the table container */
.table-responsive::-webkit-scrollbar {
    height: 8px;
}

.table-responsive::-webkit-scrollbar-track {
    background: #f1f1f1;
}

.table-responsive::-webkit-scrollbar-thumb {
    background: var(--secondary);
}

.table-responsive::-webkit-scrollbar-thumb:hover {
    background: var(--secondary-orange800);
}

/* Modal PDF icon style (optional, for better appearance) */
.pdf-icon-large {
    font-size: 64px;
    color: #e3342f;
}
</style>
