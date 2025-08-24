<?php

namespace App\Http\Controllers;

use App\Models\Asprak;
use App\Models\MataKuliah;
use App\Models\User;
use App\Models\ProfilPengguna;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Routing\Controller;

class DataAsprakController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'role:laboran']);
    }

    public function index()
    {

        $asprak = Asprak::with('dataDiriAsprak', 'kelasPraktikum.mataKuliah', 'user.profil')->get();
        $mataKuliahs = MataKuliah::all()->pluck('nama_mk', 'mk_id');
        $asprakFormatted = collect($asprak)->map(function ($item) {
            $kelas = $item->kelas_praktikum ?? $item->kelasPraktikum;
          
            return [
                'nim' => $item['user']['profil']['no_identitas'] ?? '',
                'nama' => $item['user']['profil']['nama_lengkap'] ?? '',
                'kelas' => $kelas->mata_kuliah->nama_mk ?? $kelas->mataKuliah->nama_mk . ' - ' . $kelas->kode_kelas ?? '',
                'jumlah' => $item['dataDiriAsprak']['jumlah_mahasiswa'] ?? '-',
                'wa' => $item['dataDiriAsprak']['nomor_whatsapp'] ?? '-',
                'ktp' => $item['dataDiriAsprak']['nomor_ktp'] ?? '-',
                'rekening' => $item['dataDiriAsprak']['nomor_rekening'] ?? '-',
                'mk_id' => $kelas->mata_kuliah->mk_id ?? $kelas->mataKuliah->mk_id,
            ];
        });
        return view('data-asprak.index', compact('asprakFormatted', 'mataKuliahs'));
    }

    public function show(User $asprak)
    {
        if ($asprak->role->role_name !== 'asprak') {
            return redirect()->route('data-asprak.index')
                ->with('error', 'User bukan asisten praktikum.');
        }

        $asprak->load(['profil', 'enrollments.kelas.matakuliah']);
        return view('data-asprak.show', compact('asprak'));
    }

}
