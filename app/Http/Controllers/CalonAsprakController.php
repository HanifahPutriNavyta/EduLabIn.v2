<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\MataKuliah;
use App\Models\Pengumuman;
use App\Models\PendaftaranAsprak;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;

class CalonAsprakController extends Controller
{
    //
    
    public function DashboardCasprak()
    {
        // Get only mata kuliah with active pendaftaran
        $matkuls = MataKuliah::whereHas('pendaftaranAspraks', function($q) {
                $q->where('status_pendaftaran', 1);
            })
            ->with(['pendaftaranAspraks' => function($query) {
                $query->with('calonAspraks'); // eager load calonAspraks to compute counts without extra queries
            }])
            ->withCount('kelasPraktikums')
            ->get();

        // Transform data to match the blade template structure
        $matkulData = $matkuls->map(function($matkul) {
            $pendaftaran = $matkul->pendaftaranAspraks;

            $registered = 0;
            if ($pendaftaran && isset($pendaftaran->calonAspraks)) {
                $registered = is_countable($pendaftaran->calonAspraks) ? count($pendaftaran->calonAspraks) : 0;
            }

            $hasPendaftaran = (bool) $pendaftaran;
            $hasClasses = ($matkul->kelas_praktikums_count ?? 0) > 0;
            $isFull = $pendaftaran ? ($pendaftaran->kuota > 0 && $registered >= $pendaftaran->kuota) : false;
            $available = $hasPendaftaran && !$isFull && $hasClasses && ($pendaftaran->status_pendaftaran ?? false);

            return [
                'id' => $matkul->mk_id,
                'nama' => $matkul->nama_mk,
                'kuota' => $pendaftaran ? $pendaftaran->kuota : 0,
                'registered' => $registered,
                'is_full' => $isFull,
                'has_pendaftaran' => $hasPendaftaran,
                'has_classes' => $hasClasses,
                'available' => $available,
                'ketentuan' => $pendaftaran && $pendaftaran->ketentuan 
                    ? explode("\n", $pendaftaran->ketentuan) 
                    : ['Belum ada ketentuan']
            ];
        })->toArray();

        // Get pengumuman data
        $pengumumans = Pengumuman::where('status', 1)->orderBy('created_at', 'desc')->take(2)->get();

        $data = [
            'matkulData' => $matkulData,
            'pengumumanData' => $pengumumans
        ];
        
        return view('dashboard.indexCalonAsprak', $data);
    }

    public function PengumumanCasprak()
    {
        $data = [
            'pengumumanData' => Pengumuman::where('status', 1)->orderBy('created_at', 'desc')->get()
        ];
        return view('pengumuman.list-casprak', $data);
    }

    public function DetailPengumumanCasprak($id)
    {
        $pengumuman = Pengumuman::where('pengumuman_id', $id)->where('status', 1)->firstOrFail();
        $data = [
            'pengumuman' => $pengumuman
        ];
        return view('pengumuman.detail', $data);
    }

    public function PendaftaranCasprak()
    {
        $matkuls = MataKuliah::whereHas('pendaftaranAspraks', function($q){
                $q->where('status_pendaftaran', 1);
            })
            ->with(['pendaftaranAspraks' => function($query) {
                $query->with('calonAspraks');
            }])
            ->withCount('kelasPraktikums')
            ->get();

        // Transform data to match the blade template structure (only active shown)
        $matkulData = $matkuls->map(function($matkul) {
            $pendaftaran = $matkul->pendaftaranAspraks;

            $registered = 0;
            if ($pendaftaran && isset($pendaftaran->calonAspraks)) {
                $registered = is_countable($pendaftaran->calonAspraks) ? count($pendaftaran->calonAspraks) : 0;
            }

            $hasPendaftaran = (bool) $pendaftaran; // always true here due to filter
            $hasClasses = ($matkul->kelas_praktikums_count ?? 0) > 0;
            $isFull = $pendaftaran ? ($pendaftaran->kuota > 0 && $registered >= $pendaftaran->kuota) : false;
            $available = $hasPendaftaran && !$isFull && $hasClasses && ($pendaftaran->status_pendaftaran ?? false);

            return [
                'id' => $matkul->mk_id,
                'nama' => $matkul->nama_mk,
                'kuota' => $pendaftaran ? $pendaftaran->kuota : 0,
                'registered' => $registered,
                'is_full' => $isFull,
                'has_pendaftaran' => $hasPendaftaran,
                'has_classes' => $hasClasses,
                'available' => $available,
                'ketentuan' => $pendaftaran && $pendaftaran->ketentuan 
                    ? explode("\n", $pendaftaran->ketentuan) 
                    : ['Belum ada ketentuan']
            ];
        })->toArray();

        $data = [
            'matkulData' => $matkulData
        ];

        return view('kelas-praktikum.pendaftaran-casprak', $data);
    }

    public function FormPendaftaranCasprak($id)
    {
        $matkul = MataKuliah::findOrFail($id);
        $pendaftaran = PendaftaranAsprak::where('mk_id', $matkul->mk_id)->first();
        return view('kelas-praktikum.form-pendaftaran', [
            'matkul' => $matkul,
            'pendaftaran' => $pendaftaran
        ]);
    }

    public function SubmitPendaftaran(Request $request)
    {
        $validated = $request->validate([
            'nama' => 'required|string|max:100',
            'nim' => 'required|string|max:15|regex:/^\d+$/',
            'email' => 'required|email|max:100',
            'whatsapp' => 'required|string|max:15|regex:/^\d+$/',
            'prodi' => 'required|string|max:50',
            'fakultas' => 'required|string|max:50',
            'angkatan' => 'required|numeric|digits:4',
            'status' => 'required|string|max:20',
            'matkul_id' => 'required|string',
            'bukti' => 'required|file|mimes:pdf|max:2048',
            'foto-diri' => 'required|file|mimes:pdf,jpg,jpeg,png|max:2048'
        ]);
        try {
            DB::beginTransaction();

            // Check if NIM has already registered 3 times for active pendaftaran
            $activePendaftaranCount = \App\Models\DataCalonAsprak::where('nim', $validated['nim'])
                ->whereHas('calonAsprak.pendaftaranAsprak', function($query) {
                    $query->where('status_pendaftaran', 1); // Active pendaftaran
                })
                ->count();

            if ($activePendaftaranCount >= 3) {
                return response()->json([
                    'success' => false,
                    'message' => 'Anda sudah mendaftar maksimal 3 kali untuk pendaftaran yang aktif'
                ], 400);
            }

            // Check if NIM already registered for this specific matkul
            $existingRegistration = \App\Models\DataCalonAsprak::where('nim', $validated['nim'])
                ->whereHas('calonAsprak.pendaftaranAsprak', function($query) use ($validated) {
                    $query->where('mk_id', $validated['matkul_id']);
                })
                ->first();

            if ($existingRegistration) {
                return response()->json([
                    'success' => false,
                    'message' => 'Anda sudah mendaftar untuk mata kuliah ini'
                ], 400);
            }

            // Handle file uploads using Storage
            $buktiPath = null;
            $fotoPath = null;

            if ($request->hasFile('bukti')) {
                $buktiPath = Storage::disk('public')->put('bukti-pendaftaran', $request->file('bukti'));
            }

            if ($request->hasFile('foto-diri')) {
                $fotoPath = Storage::disk('public')->put('foto-diri', $request->file('foto-diri'));
            }

            // Get pendaftaran_id for this matkul
            $pendaftaran = \App\Models\PendaftaranAsprak::where('mk_id', $validated['matkul_id'])->first();
            
            if (!$pendaftaran) {
                return response()->json([
                    'success' => false,
                    'message' => 'Pendaftaran untuk mata kuliah ini tidak ditemukan'
                ], 400);
            }

            // Create new calon asprak record
            $calonAsprak = new \App\Models\CalonAsprak();
            $calonAsprak->user_id = null; // User not authenticated yet during registration
            $calonAsprak->pendaftaran_id = $pendaftaran->pendaftaran_id;
            $calonAsprak->mk_id = $validated['matkul_id'];
            $calonAsprak->tanggal_daftar = now();
            $calonAsprak->save();

            // Create data calon asprak record
            $dataCalonAsprak = new \App\Models\DataCalonAsprak();
            $dataCalonAsprak->calon_id = $calonAsprak->calon_id;
            $dataCalonAsprak->nama = $validated['nama'];
            $dataCalonAsprak->nim = $validated['nim'];
            $dataCalonAsprak->email = $validated['email'];
            $dataCalonAsprak->prodi = $validated['prodi'];
            $dataCalonAsprak->nomor_whatsapp = $validated['whatsapp'];
            $dataCalonAsprak->tahun_ajaran = $validated['angkatan'];
            $dataCalonAsprak->bukti_file = $buktiPath;
            $dataCalonAsprak->foto_file = $fotoPath;
            $dataCalonAsprak->save();

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Pendaftaran berhasil dikirim!'
            ], 200);

        } catch (\Exception $e) {
            DB::rollback();
            
            // Clean up uploaded files if they exist
            if (isset($buktiPath) && Storage::disk('public')->exists($buktiPath)) {
                Storage::disk('public')->delete($buktiPath);
            }
            if (isset($fotoPath) && Storage::disk('public')->exists($fotoPath)) {
                Storage::disk('public')->delete($fotoPath);
            }

            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan saat menyimpan pendaftaran' . $e->getMessage()
            ], 500);
        }
    }
}
