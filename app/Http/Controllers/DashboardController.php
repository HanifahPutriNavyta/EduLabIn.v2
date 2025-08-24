<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\AbsensiPraktikan;
use App\Models\NilaiPraktikum;
use App\Models\KelasPraktikum;
use App\Models\MataKuliah;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        if (auth()->user()->hasRole('asprak')) {
            return $this->indexAsprak();
        }
        return view('dashboard.index');
    }

    public function indexAsprak()
    {
        $kelas = KelasPraktikum::with('mataKuliah')
            ->whereHas('asprak', function($query) {
                $query->where('user_id', Auth::user()->user_id);
            })
            ->get();
        
        return view('kelas-praktikum.KelasPraktikumAsprak', compact('kelas'));
    }

    public function indexAsprakKelas($kode)
    {
        // cek dulu apakah di asprak table sudah mengikuti kelas ini
        $kelas = KelasPraktikum::with('mataKuliah')->where('kelas_id', $kode)->first();
        return view('dashboard.indexAsprakKelas', compact('kelas'));
    }

   
} 