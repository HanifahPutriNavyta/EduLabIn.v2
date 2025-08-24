<?php

namespace App\Http\Controllers;

use App\Models\MataKuliah;
use App\Models\PendaftaranAsprak;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class MatakuliahController extends Controller
{
    public function index()
    {
        $mataKuliahs = MataKuliah::with('pendaftaranAspraks')->get();
        return view('matakuliah.AdminMataKuliah', compact('mataKuliahs'));
    }


    public function store(Request $request)
    {
        $request->validate([
            'nama_mk' => 'required|string|max:100',
            'ketentuan' => 'nullable|string',
            'kapasitas' => 'required|integer|min:1|max:100'
        ]);
        DB::beginTransaction();
        try {
            $matakuliah = MataKuliah::create($request->all());
            PendaftaranAsprak::create([
                'mk_id' => $matakuliah->mk_id,
                'ketentuan' => $request->ketentuan,
                'kuota' => $request->kapasitas
            ]);
            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Gagal membuat matakuliah: ' . $e->getMessage()
            ], 500);
        }
        return response()->json([
            'success' => true,
            'message' => 'Matakuliah created successfully.',
            'data' => $matakuliah
        ]);
    }

    public function show(MataKuliah $matakuliah)
    {
        $matakuliah->load('praktikum');
        return view('matakuliah.show', compact('matakuliah'));
    }

    public function edit(MataKuliah $matakuliah)
    {
        return view('matakuliah.edit', compact('matakuliah'));
    }

    public function update(Request $request)
    {
        $request->validate([
            'nama_mk' => 'required|string|max:100',
            'ketentuan' => 'nullable|string',
            'kapasitas' => 'required|integer|min:1|max:100'
        ]);
        DB::beginTransaction();
        try {
            $matakuliah = MataKuliah::find($request->mk_id);
            $matakuliah->update($request->all());
            $matakuliah->pendaftaranAspraks->update([
                'ketentuan' => $request->ketentuan,
                'kuota' => $request->kapasitas
            ]); 
            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Gagal memperbarui matakuliah: ' . $e->getMessage()
            ], 500);
        }
        return response()->json([
            'success' => true,
            'message' => 'Matakuliah updated successfully.',
            'data' => $matakuliah
        ]);
    }   

    public function destroy($id)
    {
        DB::beginTransaction();
        try {
            $matakuliah = MataKuliah::find($id);
            $matakuliah->delete();
            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Gagal menghapus matakuliah: ' . $e->getMessage()
            ], 500);
        }
        return response()->json([
            'success' => true,
            'message' => 'Matakuliah deleted successfully.'
        ]);
    }

    /**
     * Toggle only the status for a mata kuliah (laboran use-case).
     * Accepts JSON { status: 'aktif'|'nonaktif' } or boolean-like values.
     */
    public function toggleStatus(Request $request, $id)
    {
        $request->validate([
            'status' => 'required'
        ]);

        DB::beginTransaction();
        try {
            $matakuliah = MataKuliah::with(['pendaftaranAspraks', 'kelasPraktikums', 'kelasPraktikums.aspraks'])->findOrFail($id);

            // Accept various representations
            $newStatus = $request->input('status');
            $isActive = in_array($newStatus, [true, 1, '1', 'true', 'aktif', 'Aktif', 'active'], true) ? 1 : 0;

            // Manage status at PendaftaranAsprak level (not on MataKuliah)
            $pendaftaran = $matakuliah->pendaftaranAspraks;

            // Only toggle the status flag on pendaftaran; preserve ketentuan and other data
            if ($pendaftaran) {
                $pendaftaran->update(['status_pendaftaran' => $isActive]);
            } else if ($isActive) {
                // If activating and no record exists, create a minimal one
                PendaftaranAsprak::create([
                    'mk_id' => $matakuliah->mk_id,
                    'ketentuan' => '',
                    'kuota' => 0,
                    'status_pendaftaran' => 1,
                ]);
            }

            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Gagal mengubah status: ' . $e->getMessage()
            ], 500);
        }

        return response()->json([
            'success' => true,
            'message' => 'Status berhasil diubah.',
            'data' => ['status' => $isActive]
        ]);
    }
} 