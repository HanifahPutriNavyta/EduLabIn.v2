<?php

namespace App\Http\Controllers;

use App\Models\KelasPraktikum;
use App\Models\MataKuliah;
use App\Models\Asprak;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\DB;
use App\Models\PendaftaranAsprak;
class KelasPraktikumController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'role:laboran,asprak']);
    }

    public function index()
    {
        $kelasPraktikums = KelasPraktikum::with(['mataKuliah.pendaftaranAspraks'])->get();
        $mataKuliahs = MataKuliah::all();
        return view('kelas-praktikum.AdminClass', compact('kelasPraktikums', 'mataKuliahs'));
    }

    public function create() 
    {
        $matakuliah = MataKuliah::all();
        return view('kelas.create', compact('matakuliah'));
    }

    public function store(Request $request)
    {
        // 1. Validate the incoming request data
        $validatedData = $request->validate([
            'mk_id' => 'required|exists:mata_kuliahs,mk_id',
            'kode_kelas' => 'required|string|max:100',
            'kode_enroll' => 'required|string|max:100',
            'status' => 'required|in:aktif,nonaktif'
        ]);

        // Use a database transaction to ensure both updates succeed or fail together
        DB::beginTransaction();

        try {
            // 2. Create KelasPraktikum model
            $kelas = KelasPraktikum::create([
                'mk_id' => $validatedData['mk_id'],
                'kode_kelas' => $validatedData['kode_kelas'],
                'kode_enroll' => $validatedData['kode_enroll'],
                'status' => $validatedData['status'] == 'aktif' ? 1 : 0
            ]);

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Kelas praktikum berhasil dibuat!',
                'data' => $kelas
            ]);

        } catch (\Exception $e) {
            DB::rollback();
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan saat membuat kelas praktikum: ' . $e->getMessage()
            ], 500);
        }
    }

    public function show($id)
    {
        $kelasPraktikum = KelasPraktikum::with(['mataKuliah', 'dosen.user'])->findOrFail($id);
        return view('kelas-praktikum.show', compact('kelasPraktikum'));
    }

    public function edit(KelasPraktikum $kelas)
    {
        $matakuliah = MataKuliah::all();
        return view('kelas.edit', compact('kelas', 'matakuliah'));
    }

    public function update(Request $request)
    {
        // 1. Validate the incoming request data
        $validatedData = $request->validate([
            'kelas_id' => 'required|exists:kelas_praktikums,kelas_id',
            'mk_id' => 'required|exists:mata_kuliahs,mk_id',
            'kode_kelas' => 'required|string|max:100',
            'kode_enroll' => 'required|string|max:100',
            'status' => 'required|in:aktif,nonaktif'
        ]);

        // Find the kelas first
        $kelas = KelasPraktikum::find($validatedData['kelas_id']);
        if (!$kelas) {
            return response()->json([
                'success' => false,
                'message' => 'Kelas praktikum tidak ditemukan!'
            ], 404);
        }

        // Use a database transaction to ensure both updates succeed or fail together
        DB::beginTransaction();

        try {
            // 2. Update KelasPraktikum model
            $kelas->update([
                'mk_id' => $validatedData['mk_id'],
                'kode_kelas' => $validatedData['kode_kelas'],
                'kode_enroll' => $validatedData['kode_enroll'],
                'status' => $validatedData['status'] == 'aktif' ? 1 : 0
            ]);

            DB::commit(); // Commit the transaction if both updates succeed

            return response()->json([
                'success' => true,
                'message' => 'Kelas praktikum berhasil diperbarui!',
                'data' => $kelas->load(['mataKuliah.pendaftaranAspraks']) 
            ]);

        } catch (\Exception $e) {
            DB::rollBack(); // Rollback the transaction on error
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan saat memperbarui data: ' . $e->getMessage()
            ], 500);
        }
    }

    public function destroy($id)
    {        
        $kelas = KelasPraktikum::where('kelas_id', $id)->first();

        if (!$kelas) {
            return response()->json([
                'success' => false,
                'message' => 'Kelas praktikum tidak ditemukan!'
            ], 404);
        }

        try {
            $kelas->delete();

            return response()->json([
                'success' => true,
                'message' => 'Kelas praktikum berhasil dihapus!'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal menghapus kelas praktikum: ' . $e->getMessage()
            ], 500);
        }
    }

    public function generateEnrollCode()
    {
        do {
            $code = strtoupper(substr(md5(uniqid()), 0, 8));
        } while (KelasPraktikum::where('kode_enroll', $code)->exists());

        return response()->json(['code' => $code]);
    }

    public function asprak()
    {
        $kelasPraktikums = KelasPraktikum::with(['mataKuliah', 'dosen.user'])
            ->where('status', 1)
            ->get();
        
        return view('kelas-praktikum.KelasPraktikumAsprak', compact('kelasPraktikums'));
    }

    public function enrollKelas(Request $request)
    {

        $request->validate([
            'kode_enroll' => 'required|string',
        ]);

        $user = Auth::user();

        // Find kelas by kode_enroll
        $kelas = KelasPraktikum::where('kode_enroll', $request->kode_enroll)->first();

        if (!$kelas) {
            return response()->json([
                'success' => false,
                'message' => 'Kode kelas tidak ditemukan!'
            ], 400);
        }

        // Check if user is already an asprak in this class
        $existingAsprak = Asprak::where('user_id', $user->user_id)
            ->where('kelas_id', $kelas->kelas_id)
            ->first();

        if ($existingAsprak) {
            return response()->json([
                'success' => false,
                'message' => 'Anda sudah terdaftar sebagai asprak di kelas ini!'
            ], 400);
        }

        // Check capacity
        // $currentAsprakCount = Asprak::where('kelas_id', $kelas->kelas_id)->count();
        // if ($currentAsprakCount >= $kelas->kapasitas) {
        //     return response()->json([
        //         'success' => false,
        //         'message' => 'Kelas sudah penuh!'
        //     ], 400);
        // }

        // Create asprak record
        Asprak::create([
            'user_id' => $user->user_id,
            'kelas_id' => $kelas->kelas_id,
            'status' => 1
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Berhasil mendaftar sebagai asprak!'
        ]);
    }
} 