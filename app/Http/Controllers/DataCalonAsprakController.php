<?php

namespace App\Http\Controllers;

use App\Models\CalonAsprak;
use App\Models\Role;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Storage;

class DataCalonAsprakController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'role:laboran']);
    }

    public function index()
    {
        // Ambil semua data calon asprak beserta relasi user, profil, dan matakuliah
        $data = CalonAsprak::with([
            'matakuliah', 
            'dataCalonAsprak'
        ])
        ->orderBy('created_at', 'desc')
        ->get()
        ->map(function ($item) {
            return [
                'nim' => $item->dataCalonAsprak->nim ?? '-',
                'nama' => $item->dataCalonAsprak->nama ?? '-',
                'prodi' => $item->dataCalonAsprak->prodi ?? '-',
                'email' => $item->dataCalonAsprak->email ?? '-',
                'wa' => $item->dataCalonAsprak->nomor_whatsapp ?? '-',
                'kelas' => $item->matakuliah->nama_mk ?? '-',
                'tahun' => $item->created_at->format('Y'),
                'foto' => $item->dataCalonAsprak->foto_file 
                    ? Storage::url($item->dataCalonAsprak->foto_file) 
                    : '-',
                'bukti' => $item->dataCalonAsprak->bukti_file 
                    ? Storage::url($item->dataCalonAsprak->bukti_file) 
                    : '-',
                'mk_id' => $item->matakuliah->mk_id ?? '-',
            ];
        }); 
        $mataKuliahs = CalonAsprak::with('matakuliah')
            ->get()
            ->pluck('matakuliah.nama_mk', 'matakuliah.mk_id');

        return view('data-calon-asprak.index', compact('data', 'mataKuliahs'));
    }

   
} 