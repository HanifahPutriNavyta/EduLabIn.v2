<?php

namespace App\Http\Controllers;

use App\Models\NilaiPraktikum;
use App\Models\KelasPraktikum;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Routing\Controller;

class InformasiNilaiController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('role:dosen,asprak');
    }

    public function index()
    {
        $kelas = KelasPraktikum::with('matakuliah')
            ->where('dosen_id', Auth::user()->dosen->dosen_id)
            ->get();
        return view('informasi-nilai.MkInformasiNilaiDosen', compact('kelas'));
    }

    public function indexAsprak($kelas_id)
    {
        $user = Auth::user();
        $asprak = $user->asprak;

        if (!$asprak || !$asprak->contains('kelas_id', $kelas_id)) {
            return redirect()->route('dashboard')->with('error', 'Anda tidak terdaftar dalam kelas praktikum ini.');
        }
        
        // Get nilai praktikum data for this asprak and kelas
        $nilaiPraktikans = NilaiPraktikum::with(['kelasPraktikum.mataKuliah'])
            ->whereHas('kelasPraktikum', function ($query) use ($kelas_id) {
                $query->where('kelas_id', $kelas_id);
            })
            ->orderBy('created_at', 'desc')
            ->get()
            ->map(function($nilai) {
                return [
                    'nilai_id' => $nilai->nilai_id,
                    'judul' => $nilai->judul ?? 'Tidak ada judul',
                    'tanggal' => $nilai->tanggal ? $nilai->tanggal->format('d F Y') : 'N/A',
                    'deskripsi' => $nilai->deskripsi ?? 'Tidak ada deskripsi',
                    'file_nilai_praktikan' => $nilai->upload_file,
                    'matkul_kode' => $nilai->kelasPraktikum->mataKuliah->mk_id ?? 'N/A',
                ];
            });

        // Get mata kuliah data for this kelas
        $asprakData = $asprak->where('kelas_id', $kelas_id)->first();
        $matkuls = collect([
            ['kode' => $asprakData->kelasPraktikum->mataKuliah->mk_id ?? 'N/A', 'nama_mk' => $asprakData->kelasPraktikum->mataKuliah->nama_mk . ' - ' . $asprakData->kelasPraktikum->kode_kelas ?? 'N/A']
        ]);
        
        return view('informasi-nilai.NilaiPraktikan', compact('nilaiPraktikans', 'matkuls', 'kelas_id'));
    }

    public function create($kelas_id)
    {
        $kelas = KelasPraktikum::findOrFail($kelas_id);
        
        return view('informasi-nilai.CreateNilaiPraktikan', compact('kelas'));
    }

    public function createAsprak($kelas_id)
    {
        $user = Auth::user();
        $asprak = $user->asprak;

        if (!$asprak || !$asprak->contains('kelas_id', $kelas_id)) {
            return redirect()->route('dashboard')->with('error', 'Anda tidak terdaftar dalam kelas praktikum ini.');
        }

        $kelas = KelasPraktikum::findOrFail($kelas_id);
        
        return view('informasi-nilai.CreateNilaiPraktikan', compact('kelas', 'kelas_id'));
    }

    public function storeAsprak(Request $request)
    {
        $user = Auth::user();
        $asprak = $user->asprak;
        $kelas_id = $request->kelas_id;

        if (!$asprak || !$asprak->contains('kelas_id', $kelas_id)) {
            return redirect()->route('dashboard')->with('error', 'Anda tidak terdaftar dalam kelas praktikum ini.');
        }

        $request->validate([
            'judul' => 'required|string|max:100',
            'deskripsi' => 'required|string',
            'tanggal' => 'required|date',
            'upload_file' => 'required|file|mimes:pdf|max:2048',
            'kelas_id' => 'required|exists:kelas_praktikums,kelas_id'
        ]);

        $data = [
            'kelas_id' => $kelas_id,
            'asprak_id' => $asprak->where('kelas_id', $kelas_id)->first()->asprak_id,
            'judul' => $request->judul,
            'deskripsi' => $request->deskripsi,
            'tanggal' => $request->tanggal,
        ];

        // Handle file upload
        if ($request->hasFile('upload_file')) {
            $file = $request->file('upload_file');
            $fileName = time() . '_' . $file->getClientOriginalName();
            $filePath = $file->storeAs('nilai', $fileName, 'public');
            $data['upload_file'] = $fileName;
        }

        NilaiPraktikum::create($data);

        return redirect()->route('informasi-nilai-asprak.index', $kelas_id)
            ->with('success', 'Nilai praktikum berhasil ditambahkan.');
    }

    public function editAsprak($kelas_id, $nilai_id)
    {
        $user = Auth::user();
        $asprak = $user->asprak;

        if (!$asprak || !$asprak->contains('kelas_id', $kelas_id)) {
            return redirect()->route('dashboard')->with('error', 'Anda tidak terdaftar dalam kelas praktikum ini.');
        }

        // Find the nilai record
        $nilaiPraktikan = NilaiPraktikum::where('nilai_id', $nilai_id)
            ->where('kelas_id', $kelas_id)
            ->first();

        if (!$nilaiPraktikan) {
            return redirect()->route('informasi-nilai-asprak.index', $kelas_id)
                ->with('error', 'Nilai praktik tidak ditemukan.');
        }

        // Verify ownership: the asprak record for this user and kelas
        $asprakRecord = $asprak->where('kelas_id', $kelas_id)->first();
        if (!$asprakRecord || $nilaiPraktikan->asprak_id != $asprakRecord->asprak_id) {
            return redirect()->route('informasi-nilai-asprak.index', $kelas_id)
                ->with('error', 'Anda tidak memiliki akses untuk mengedit nilai ini.');
        }

        $kelas = KelasPraktikum::findOrFail($kelas_id);

        return view('informasi-nilai.EditNilaiPraktikan', compact('nilaiPraktikan', 'kelas', 'kelas_id'));
    }

    public function updateAsprak(Request $request, $kelas_id, $nilai_id)
    {
        $user = Auth::user();
        $asprak = $user->asprak;

        if (!$asprak || !$asprak->contains('kelas_id', $kelas_id)) {
            return redirect()->route('dashboard')->with('error', 'Anda tidak terdaftar dalam kelas praktikum ini.');
        }

        $nilaiPraktikan = NilaiPraktikum::where('nilai_id', $nilai_id)
            ->where('kelas_id', $kelas_id)
            ->first();

        if (!$nilaiPraktikan) {
            return redirect()->route('informasi-nilai-asprak.index', $kelas_id)
                ->with('error', 'Nilai praktik tidak ditemukan.');
        }


        $request->validate([
            'judul' => 'required|string|max:100',
            'deskripsi' => 'nullable|string',
            'tanggal' => 'required|date',
            'upload_file' => 'nullable|file|mimes:pdf|max:2048',
            'kelas_id' => 'required|exists:kelas_praktikums,kelas_id'
        ]);

        $data = [
            'judul' => $request->judul,
            'deskripsi' => $request->deskripsi,
            'tanggal' => $request->tanggal,
        ];

        // Handle file upload
        if ($request->hasFile('upload_file')) {
            // Delete old file if exists
            if ($nilaiPraktikan->upload_file && Storage::disk('public')->exists('nilai/' . $nilaiPraktikan->upload_file)) {
                Storage::disk('public')->delete('nilai/' . $nilaiPraktikan->upload_file);
            }

            $file = $request->file('upload_file');
            $fileName = time() . '_' . $file->getClientOriginalName();
            $filePath = $file->storeAs('nilai', $fileName, 'public');
            $data['upload_file'] = $fileName;
        }

        $nilaiPraktikan->update($data);

        return redirect()->route('informasi-nilai-asprak.index', $kelas_id)
            ->with('success', 'Nilai praktikum berhasil diperbarui.');
    }

    public function destroyAsprak($kelas_id, $nilai_id)
    {
        $user = Auth::user();
        $asprak = $user->asprak;

        if (!$asprak || !$asprak->contains('kelas_id', $kelas_id)) {
            return redirect()->route('dashboard')->with('error', 'Anda tidak terdaftar dalam kelas praktikum ini.');
        }

        $nilaiPraktikan = NilaiPraktikum::find($nilai_id);

        if (!$nilaiPraktikan || $nilaiPraktikan->kelas_id != $kelas_id) {
            return redirect()->route('informasi-nilai-asprak.index', $kelas_id)
                ->with('error', 'Nilai praktik tidak ditemukan.');
        }

        // $asprakRecord = $asprak->where('kelas_id', $kelas_id)->first();
        // if (!$asprakRecord || $nilaiPraktikan->asprak_id != $asprakRecord->asprak_id) {
        //     return redirect()->route('informasi-nilai-asprak.index', $kelas_id)
        //         ->with('error', 'Anda tidak memiliki akses untuk menghapus nilai ini.');
        // }

        // Delete file if exists
        if ($nilaiPraktikan->upload_file && Storage::disk('public')->exists('nilai/' . $nilaiPraktikan->upload_file)) {
            Storage::disk('public')->delete('nilai/' . $nilaiPraktikan->upload_file);
        }

        $nilaiPraktikan->delete();

        return redirect()->route('informasi-nilai-asprak.index', $kelas_id)
            ->with('success', 'Nilai praktikum berhasil dihapus.');
    }

    public function show(KelasPraktikum $matkul)
    {
        $nilai = NilaiPraktikum::where('kelas_id', $matkul->kelas_id)
            ->with(['asprak.user.profil'])
            ->get();
        
        return view('informasi-nilai.FileNilaiDosen', compact('matkul', 'nilai'));
    }


    public function download(NilaiPraktikum $nilai)
    {
        if (!$nilai->upload_file) {
            return redirect()->back()->with('error', 'File tidak ditemukan.');
        }

        $filePath = storage_path('app/public/nilai/' . $nilai->upload_file);
        
        if (!file_exists($filePath)) {
            return redirect()->back()->with('error', 'File tidak ditemukan.');
        }

        return response()->download($filePath, $nilai->judul . '.pdf');
    }
} 