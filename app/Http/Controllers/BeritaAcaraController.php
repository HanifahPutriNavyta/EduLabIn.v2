<?php

namespace App\Http\Controllers;

use App\Models\BeritaAcara;
use App\Models\KelasPraktikum;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;
use Illuminate\Routing\Controller;

class BeritaAcaraController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        // Allow index, show, and download actions to be reachable without role restriction
        $this->middleware('role:asprak,dosen')->except(['index', 'show', 'download', 'downloadBukti']);
    }

    public function indexAsprak($kelas_id)
    {
        $user = Auth::user();
        $asprak = $user->asprak;

        if (!$asprak || !$asprak->contains('kelas_id', $kelas_id)) {
            return redirect()->route('dashboard')->with('error', 'Anda tidak terdaftar dalam kelas praktikum ini.');
        }

        $beritaAcaras = BeritaAcara::with(['kelasPraktikum.mataKuliah'])
            ->whereHas('kelasPraktikum', function ($query) use ($kelas_id) {
                $query->where('kelas_id', $kelas_id);
            })
            ->orderBy('created_at', 'desc')
            ->get();

        return view('berita-acara.BeritaAcaraAsprak', compact('beritaAcaras', 'kelas_id'));
    }

    public function indexDosen()
    {
        $kelas = \App\Models\KelasPraktikum::with('matakuliah')->where('dosen_id', Auth::user()->dosen->dosen_id)
            ->get();
        return view('berita-acara.MKBeritaAcaraDosen', compact('kelas'));
    }

    public function showKelas($kelas_id)
    {
        $kelas = \App\Models\KelasPraktikum::with('matakuliah')->findOrFail($kelas_id);
        $pertemuans = \App\Models\BeritaAcara::where('kelas_id', $kelas_id)->get();
        // Siapkan data $pertemuans sesuai kebutuhan view
        return view('berita-acara.BeritaAcaraDosenList', compact('kelas', 'pertemuans'));
    }

    public function create($kelas_id)
    {
        $user = Auth::user();
        $asprak = $user->asprak;

        if (!$asprak || !$asprak->contains('kelas_id', $kelas_id)) {
            return redirect()->route('dashboard')->with('error', 'Anda tidak terdaftar dalam kelas praktikum ini.');
        }

        // Get available classes for the asprak based on kelas_id from asprak
        $kelas = KelasPraktikum::where('kelas_id', $kelas_id)
            ->with('mataKuliah')->get();

        // Data untuk form create
        $submitUrl = route('berita-acara.store');
        $submitText = 'Submit';
        $beritaAcara = null; // untuk form create

        return view('berita-acara.CreateBeritaAcaraAsprak', compact('kelas', 'submitUrl', 'submitText', 'beritaAcara', 'kelas_id'));
    }


    public function store(Request $request)
    {
        $request->validate([
            'judul' => 'required|string|max:255',
            'tanggal' => 'required|date',
            'tipe_pertemuan' => 'required|in:Luring,Daring',
            'file-input-beritaAcara' => 'required|file|mimes:pdf|max:10240',
            'file-input-buktiPertemuan' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:10240',
            'kelas_id' => 'required|exists:kelas_praktikums,kelas_id'
        ]);

        $user = Auth::user();
        $asprak = $user->asprak;
        $kelas_id = $request->kelas_id;

        if (!$asprak || !$asprak->contains('kelas_id', $kelas_id)) {
            return redirect()->route('dashboard')->with('error', 'Anda tidak terdaftar dalam kelas praktikum ini.');
        }

        // Get asprak_id from the asprak collection
        $asprakData = $asprak->where('kelas_id', $kelas_id)->first();

        // Prepare data for database
        $data = [
            'kelas_id' => $kelas_id,
            'asprak_id' => $asprakData->asprak_id,
            'judul' => $request->judul,
            'tanggal_kegiatan' => $request->tanggal,
            'tipe_pertemuan' => strtolower($request->tipe_pertemuan),
            'deskripsi_kegiatan' => $request->judul
        ];

        // Handle file berita acara
        if ($request->hasFile('file-input-beritaAcara')) {
            $file = $request->file('file-input-beritaAcara');
            $fileName = time() . '_berita_acara_' . $file->getClientOriginalName();
            $file->storeAs('berita-acara/file', $fileName, 'public');
            $data['upload_berita_acara'] = $fileName;
        }

        // Handle bukti pertemuan (khusus untuk daring)
        if ($request->hasFile('file-input-buktiPertemuan') && strtolower($request->tipe_pertemuan) === 'daring') {
            $bukti = $request->file('file-input-buktiPertemuan');
            $buktiName = time() . '_bukti_' . $bukti->getClientOriginalName();
            $bukti->storeAs('berita-acara/foto', $buktiName, 'public');
            $data['upload_bukti_pertemuan'] = $buktiName;
        }

        BeritaAcara::create($data);
        return redirect()->route('berita-acara.indexAsprak', $kelas_id)->with('success', 'Berita acara berhasil dibuat.');
    }

    public function show(BeritaAcara $beritaAcara)
    {
        $beritaAcara->load(['kelas.matakuliah', 'asprak.profil']);
        return view('berita-acara.show', compact('beritaAcara'));
    }

    public function edit(BeritaAcara $beritaAcara)
    {
        $user = Auth::user();
        $asprak = $user->asprak;
        $kelas_id = $beritaAcara->kelas_id;

        if (!$asprak || !$asprak->contains('kelas_id', $kelas_id)) {
            return redirect()->route('dashboard')->with('error', 'Anda tidak terdaftar dalam kelas praktikum ini.');
        }

       

        // Get available classes for the asprak based on kelas_id from asprak
        $kelas = KelasPraktikum::where('kelas_id', $kelas_id)
            ->with('mataKuliah')->get();

        // Data untuk form edit
        $submitUrl = route('berita-acara.update', $beritaAcara->berita_id);
        $submitText = 'Update';

        return view('berita-acara.EditBeritaAcaraAsprak', compact('beritaAcara', 'kelas', 'submitUrl', 'submitText', 'kelas_id'));
    }

    public function update(Request $request, BeritaAcara $beritaAcara)
    {
        $user = Auth::user();
        $asprak = $user->asprak;
        $kelas_id = $beritaAcara->kelas_id;

        if (!$asprak || !$asprak->contains('kelas_id', $kelas_id)) {
            return redirect()->route('dashboard')->with('error', 'Anda tidak terdaftar dalam kelas praktikum ini.');
        }

        $request->validate([
            'judul' => 'required|string|max:255',
            'tanggal' => 'required|date',
            'tipe_pertemuan' => 'required|in:Luring,Daring',
            'file-input-beritaAcara' => 'nullable|file|mimes:pdf|max:10240',
            'file-input-buktiPertemuan' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:10240'
        ]);

        // Prepare data for update
        $data = [
            'judul' => $request->judul,
            'tanggal_kegiatan' => $request->tanggal,
            'tipe_pertemuan' => strtolower($request->tipe_pertemuan),
            'deskripsi_kegiatan' => $request->judul
        ];

        // Handle file berita acara
        if ($request->hasFile('file-input-beritaAcara')) {
            // Delete old file
            if ($beritaAcara->upload_berita_acara) {
                Storage::delete('public/berita-acara/file/' . $beritaAcara->upload_berita_acara);
            }

            $file = $request->file('file-input-beritaAcara');
            $fileName = time() . '_berita_acara_' . $file->getClientOriginalName();
            $file->storeAs('berita-acara/file', $fileName, 'public');
            $data['upload_berita_acara'] = $fileName;
        }

        // Handle bukti pertemuan
        if ($request->hasFile('file-input-buktiPertemuan')) {
            // Delete old file
            if ($beritaAcara->upload_bukti_pertemuan) {
                Storage::delete('public/berita-acara/foto/' . $beritaAcara->upload_bukti_pertemuan);
            }

            $bukti = $request->file('file-input-buktiPertemuan');
            $buktiName = time() . '_bukti_' . $bukti->getClientOriginalName();
            $bukti->storeAs('berita-acara/foto', $buktiName, 'public');
            $data['upload_bukti_pertemuan'] = $buktiName;
        }

        $beritaAcara->update($data);
        return redirect()->route('berita-acara.indexAsprak', $kelas_id)->with('success', 'Berita acara berhasil diperbarui.');
    }

    public function destroy(BeritaAcara $beritaAcara)
    {
        $user = Auth::user();
        $asprak = $user->asprak;
        $kelas_id = $beritaAcara->kelas_id;

        if (!$asprak || !$asprak->contains('kelas_id', $kelas_id)) {
            return redirect()->route('dashboard')->with('error', 'Anda tidak terdaftar dalam kelas praktikum ini.');
        }

        // Delete files from storage
        if ($beritaAcara->upload_berita_acara) {
            Storage::delete('public/berita-acara/file/' . $beritaAcara->upload_berita_acara);
        }
        if ($beritaAcara->upload_bukti_pertemuan) {
            Storage::delete('public/berita-acara/foto/' . $beritaAcara->upload_bukti_pertemuan);
        }

        $beritaAcara->delete();
        return redirect()->route('berita-acara.indexAsprak', $kelas_id)->with('success', 'Berita acara berhasil dihapus.');
    }

    public function download(BeritaAcara $beritaAcara)
    {
        if (!$beritaAcara->upload_berita_acara) {
            return redirect()->back()->with('error', 'File tidak ditemukan.');
        }

        $disk = Storage::disk('public');
        $relative = 'berita-acara/file/' . $beritaAcara->upload_berita_acara;

        if (!$disk->exists($relative)) {
            Log::warning('Download failed - file missing on disk', ['path' => $relative, 'berita_id' => $beritaAcara->berita_id]);
            return redirect()->back()->with('error', 'File tidak ditemukan.');
        }

        $filePath = $disk->path($relative);
        return response()->download($filePath, $beritaAcara->judul . '.pdf');
    }

    public function downloadBukti(BeritaAcara $beritaAcara)
    {
        if (!$beritaAcara->upload_bukti_pertemuan) {
            return redirect()->back()->with('error', 'File tidak ditemukan.');
        }

        $disk = Storage::disk('public');
        $relative = 'berita-acara/foto/' . $beritaAcara->upload_bukti_pertemuan;

        if (!$disk->exists($relative)) {
            Log::warning('Download bukti failed - file missing on disk', ['path' => $relative, 'berita_id' => $beritaAcara->berita_id]);
            return redirect()->back()->with('error', 'File tidak ditemukan.');
        }

        $filePath = $disk->path($relative);
        return response()->download($filePath, $beritaAcara->judul . '_bukti.pdf');
    }
}
