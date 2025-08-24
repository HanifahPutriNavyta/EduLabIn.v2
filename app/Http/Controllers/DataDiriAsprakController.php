<?php

namespace App\Http\Controllers;

use App\Models\DataDiriAsprak;
use App\Models\KelasPraktikum;
use App\Models\Asprak;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Routing\Controller;

class DataDiriAsprakController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('role:asprak');
    }

    public function show($kelas_id)
    {
        $user = Auth::user();
        $aspraks = $user->asprak; // Collection of asprak
        
        if ($aspraks->isEmpty()) {
            return redirect()->route('dashboard')->with('error', 'Anda tidak terdaftar sebagai asprak.');
        }

        // Validasi akses asprak ke kelas ini - check if user has asprak record for this kelas
        $asprak = $aspraks->where('kelas_id', $kelas_id)->first();
        if (!$asprak) {
            abort(403, 'Anda tidak memiliki akses ke kelas ini.');
        }
        
        // Get existing data if available for this specific kelas
        $dataDiri = DataDiriAsprak::where('asprak_id', $asprak->asprak_id)
                                 ->where('kelas_id', $kelas_id)
                                 ->first();
        
        // Get the specific kelas information
        $kelas = KelasPraktikum::with('mataKuliah')->findOrFail($kelas_id);
        
        return view('data-asprak.DataDiriAsprak', compact('dataDiri', 'kelas', 'kelas_id'));
    }

    public function updateData(Request $request, $kelas_id)
    {
        $request->validate([
            'jumlah_mahasiswa' => 'required|integer|min:1',
            'nomor_whatsapp' => 'required|string|max:20',
            'nomor_ktp' => 'required|string|max:20',
            'nomor_rekening' => 'required|string|max:50',
        ]);

        $user = Auth::user();
        $aspraks = $user->asprak; // Collection of asprak
        
        if ($aspraks->isEmpty()) {
            return response()->json(['success' => false, 'message' => 'Anda tidak terdaftar sebagai asprak.']);
        }

        // Validasi akses asprak ke kelas ini - check if user has asprak record for this kelas
        $asprak = $aspraks->where('kelas_id', $kelas_id)->first();
        if (!$asprak) {
            return response()->json(['success' => false, 'message' => 'Anda tidak memiliki akses ke kelas ini.']);
        }

        try {
            // Find existing data or create new
            $dataDiri = DataDiriAsprak::where('asprak_id', $asprak->asprak_id)
                                     ->where('kelas_id', $kelas_id)
                                     ->first();

            if ($dataDiri) {
                // Update existing data
                $dataDiri->update([
                    'nama' => $user->profil->nama_lengkap,
                    'nim' => $user->profil->no_identitas,
                    'jumlah_mahasiswa' => $request->jumlah_mahasiswa,
                    'nomor_whatsapp' => $request->nomor_whatsapp,
                    'nomor_ktp' => $request->nomor_ktp,
                    'nomor_rekening' => $request->nomor_rekening,
                ]);
            } else {
                // Create new data
                DataDiriAsprak::create([
                    'asprak_id' => $asprak->asprak_id,
                    'kelas_id' => $kelas_id,
                    'nama' => $request->nama,
                    'nim' => $request->nim,
                    'jumlah_mahasiswa' => $request->jumlah_mahasiswa,
                    'nomor_whatsapp' => $request->nomor_whatsapp,
                    'nomor_ktp' => $request->nomor_ktp,
                    'nomor_rekening' => $request->nomor_rekening,
                ]);
            }

            return response()->json(['success' => true, 'message' => 'Data berhasil disimpan.']);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'Terjadi kesalahan: ' . $e->getMessage()]);
        }
    }
    
    public function update(Request $request)
    {
        $request->validate([
            'nama_lengkap' => 'required|string|max:255',
            'jenis_kelamin' => 'required|in:L,P',
            'tanggal_lahir' => 'required|date',
            'alamat' => 'required|string',
            'nomor_telepon' => 'required|string|max:20',
            'foto_profil' => 'nullable|image|mimes:jpeg,png,jpg|max:2048'
        ]);

        $profil = Auth::user()->profil;
        $data = $request->all();

        if ($request->hasFile('foto_profil')) {
            // Delete old foto
            if ($profil->foto_profil) {
                Storage::delete('public/profil/' . $profil->foto_profil);
            }
            
            $foto = $request->file('foto_profil');
            $fotoName = time() . '_' . $foto->getClientOriginalName();
            $foto->storeAs('public/profil', $fotoName);
            $data['foto_profil'] = $fotoName;
        }

        $profil->update($data);
        return redirect()->route('data-diri-asprak.show')
            ->with('success', 'Data diri berhasil diperbarui.');
    }
    
} 