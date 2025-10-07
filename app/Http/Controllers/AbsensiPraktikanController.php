<?php

namespace App\Http\Controllers;

use App\Models\AbsensiPraktikan;
use App\Models\Asprak;
use App\Models\KelasPraktikum;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AbsensiPraktikanController extends Controller
{
    public function index($kelas_id)
    {
        $user = Auth::user();
        $asprak = $user->asprak;

        if (!$asprak || !$asprak->contains('kelas_id', $kelas_id)) {
            return redirect()->route('dashboard')->with('error', 'Anda tidak terdaftar dalam kelas praktikum ini.');
        }
        

        $pertemuans = AbsensiPraktikan::with(['kelasPraktikum.mataKuliah'])
            ->whereHas('kelasPraktikum', function ($query) use ($kelas_id) {
                $query->where('kelas_id', $kelas_id);
            })
            ->orderBy('created_at', 'desc')
            ->get();

        return view('absensi-praktikan.AbsensiPraktikan', compact('pertemuans', 'kelas_id'));
    }

    public function create($kelas_id)
    {
        $user = Auth::user();
        $asprak = $user->asprak;

        if (!$asprak || !$asprak->contains('kelas_id', $kelas_id)) {
            return redirect()->route('dashboard')->with('error', 'Anda tidak terdaftar dalam kelas praktikum ini.');
        }
        
        $kelas = KelasPraktikum::with('mataKuliah')->findOrFail($kelas_id);
        return view('absensi-praktikan.CreateAbsensi', compact('kelas'));
    }

    public function store(Request $request)
    {
        $user = Auth::user();
        $asprak = $user->asprak;
        $kelas_id = $request->kelas_id;

        if (!$asprak || !$asprak->contains('kelas_id', $kelas_id)) {
            return redirect()->route('dashboard')->with('error', 'Anda tidak terdaftar dalam kelas praktikum ini.');
        }
        
        $request->validate([
            'judul' => 'required|string|max:255',
            'kelas_id' => 'required|exists:kelas_praktikums,kelas_id',
            'tanggal' => 'required|date|before_or_equal:today',
            'deskripsi' => 'required|string|max:1000',
            'upload_file' => 'required|file|mimes:pdf|max:2048',
        ], [
            'judul.required' => 'Judul absensi wajib diisi.',
            'judul.max' => 'Judul tidak boleh lebih dari 255 karakter.',
            'kelas_id.required' => 'Kelas praktikum wajib dipilih.',
            'kelas_id.exists' => 'Kelas praktikum yang dipilih tidak valid.',
            'tanggal.required' => 'Tanggal wajib diisi.',
            'tanggal.date' => 'Format tanggal tidak valid.',
            'tanggal.before_or_equal' => 'Tanggal tidak boleh lebih dari hari ini.',
            'deskripsi.required' => 'Deskripsi wajib diisi.',
            'deskripsi.max' => 'Deskripsi tidak boleh lebih dari 1000 karakter.',
            'upload_file.file' => 'File yang diupload tidak valid.',
            'upload_file.mimes' => 'File harus berformat PDF.',
            'upload_file.max' => 'Ukuran file tidak boleh lebih dari 2MB.',
        ]);

        // Prepare data for saving
        $data = [
            'judul' => $request->judul,
            'kelas_id' => $request->kelas_id,
            'tanggal' => $request->tanggal,
            'deskripsi' => $request->deskripsi,
        ];

        // Handle file upload if exists
        if ($request->hasFile('upload_file')) {
            $file = $request->file('upload_file');
            $filename = time() . '_' . $file->getClientOriginalName();
            $filePath = $file->storeAs('absensi-praktikan/file', $filename, 'public');
            $data['upload_file'] = $filePath;
        }

        // Create the absensi record
        AbsensiPraktikan::create($data);

        // Redirect with success message
        return redirect()->route('absensi-praktikan.index', $kelas_id)
            ->with('success', 'Absensi praktikan berhasil ditambahkan.');
    }

    public function show(AbsensiPraktikan $absensiPraktikan)
    {
        $user = Auth::user();
        $asprak = $user->asprak;
        $kelas_id = $absensiPraktikan->kelas_id;

        if (!$asprak || !$asprak->contains('kelas_id', $kelas_id)) {
            return redirect()->route('dashboard')->with('error', 'Anda tidak terdaftar dalam kelas praktikum ini.');
        }

        $absensiPraktikan->load(['kelas', 'user']);
        return view('absensi-praktikan.show', compact('absensiPraktikan'));
    }

    public function edit(AbsensiPraktikan $absensiPraktikan)
    {
        $user = Auth::user();
        $asprak = $user->asprak;
        $kelas_id = $absensiPraktikan->kelas_id;

        if (!$asprak || !$asprak->contains('kelas_id', $kelas_id)) {
            return redirect()->route('dashboard')->with('error', 'Anda tidak terdaftar dalam kelas praktikum ini.');
        }

        $kelas = KelasPraktikum::with('mataKuliah')->get();
        $absensi = $absensiPraktikan; // Alias untuk konsistensi dengan view
        return view('absensi-praktikan.EditAbsensi', compact('absensi', 'kelas'));
    }

    public function update(Request $request, AbsensiPraktikan $absensiPraktikan)
    {
        $user = Auth::user();
        $asprak = $user->asprak;
        $kelas_id = $absensiPraktikan->kelas_id;

        // if (!$asprak || !$asprak->contains('kelas_id', $kelas_id)) {
        //     return redirect()->route('dashboard')->with('error', 'Anda tidak terdaftar dalam kelas praktikum ini.');
        // }

        $request->validate([
            'judul' => 'required|string|max:255',
            'kelas_id' => 'required|exists:kelas_praktikums,kelas_id',
            'tanggal' => 'required|date|before_or_equal:today',
            'deskripsi' => 'required|string|max:1000',
            'upload_file' => 'nullable|file|mimes:pdf,doc,docx,jpg,jpeg,png|max:2048',
        ], [
            'judul.required' => 'Judul absensi wajib diisi.',
            'judul.max' => 'Judul tidak boleh lebih dari 255 karakter.',
            'kelas_id.required' => 'Kelas praktikum wajib dipilih.',
            'kelas_id.exists' => 'Kelas praktikum yang dipilih tidak valid.',
            'tanggal.required' => 'Tanggal wajib diisi.',
            'tanggal.date' => 'Format tanggal tidak valid.',
            'tanggal.before_or_equal' => 'Tanggal tidak boleh lebih dari hari ini.',
            'deskripsi.required' => 'Deskripsi wajib diisi.',
            'deskripsi.max' => 'Deskripsi tidak boleh lebih dari 1000 karakter.',
            'upload_file.file' => 'File yang diupload tidak valid.',
            'upload_file.mimes' => 'File harus berformat PDF, DOC, DOCX, JPG, JPEG, atau PNG.',
            'upload_file.max' => 'Ukuran file tidak boleh lebih dari 2MB.',
        ]);

        // Prepare data for updating
        $data = [
            'judul' => $request->judul,
            'kelas_id' => $request->kelas_id,
            'tanggal' => $request->tanggal,
            'deskripsi' => $request->deskripsi,
        ];

        // Handle file upload if exists
        if ($request->hasFile('upload_file')) {
            $file = $request->file('upload_file');
            $filename = time() . '_' . $file->getClientOriginalName();
            $filePath = $file->storeAs('absensi-praktikan/file', $filename, 'public');
            $data['upload_file'] = $filePath;
        }

        // Update the absensi record
        $absensiPraktikan->update($data);

        return redirect()->route('absensi-praktikan.index', $absensiPraktikan->kelas_id)->with('success', 'Absensi berhasil diperbarui.');
    }

    public function destroy(AbsensiPraktikan $absensiPraktikan)
    {
        $user = Auth::user();
        $asprak = $user->asprak;
        $kelas_id = $absensiPraktikan->kelas_id;

        if (!$asprak || !$asprak->contains('kelas_id', $kelas_id)) {
            return redirect()->route('dashboard')->with('error', 'Anda tidak terdaftar dalam kelas praktikum ini.');
        }

        $absensiPraktikan->delete();
        return redirect()->route('absensi-praktikan.index', $kelas_id)->with('success', 'Absensi berhasil dihapus.');
    }
} 