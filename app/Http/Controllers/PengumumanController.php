<?php

namespace App\Http\Controllers;

use App\Models\Pengumuman;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Routing\Controller;

class PengumumanController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'role:laboran']);
    }

    public function index()
    {
        $pengumumans = Pengumuman::orderBy('created_at', 'desc')->get();
        return view('pengumuman.index', compact('pengumumans'));
    }

    public function store(Request $request)
    {
        
        $request->validate([
            'judul' => 'required|string|max:255',
            'deskripsi' => 'required|string',
            'gambar' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        try {
            
            $pengumuman = Pengumuman::create([
                'judul' => $request->judul,
                'deskripsi' => $request->deskripsi,
                'created_by' => Auth::user()->user_id,
            ]);

            if ($request->hasFile('gambar')) {
                $file = $request->file('gambar');
                $filename = time() . '_' . $file->getClientOriginalName();
                Storage::disk('public')->putFileAs('pengumuman', $file, $filename);
                $pengumuman->gambar = $filename;
                $pengumuman->save();
            }
            
            return response()->json([
                'success' => true,
                'message' => 'Pengumuman berhasil dibuat',
                'pengumuman' => $pengumuman
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal membuat pengumuman: ' . $e->getMessage()
            ], 500);
        }
    }

   
    public function update(Request $request, Pengumuman $pengumuman)
    {
        $request->validate([
            'judul' => 'required|string|max:255',
            'deskripsi' => 'required|string'
        ]);
        if ($request->hasFile('gambar')) {
            $request->validate([
                'gambar' => 'image|mimes:jpeg,png,jpg,gif|max:2048',
            ]);

            // Delete old image if exists
            if ($pengumuman->gambar) {
                Storage::delete('public/pengumuman/' . $pengumuman->gambar);
            }

            $file = $request->file('gambar');
            $filename = time() . '_' . $file->getClientOriginalName();
            Storage::disk('public')->putFileAs('pengumuman', $file, $filename);
            $pengumuman->gambar = $filename;
        }
        $data = $request->only(['judul', 'deskripsi']);
        if ($request->has('status')) {
            $data['status'] = (int) $request->status;
        }
        $pengumuman->update($data);
        
        return response()->json([
            'success' => true,
            'message' => 'Pengumuman updated successfully'
        ]);
    }

    public function destroy(Pengumuman $pengumuman)
    {
        $pengumuman->delete();
        
        return response()->json([
            'success' => true,
            'message' => 'Pengumuman deleted successfully'
        ]);
    }

    /**
     * Toggle or set numeric status (0/1) for a pengumuman.
     */
    public function toggleStatus(Request $request, Pengumuman $pengumuman)
    {
        $request->validate([
            'status' => 'required|in:0,1'
        ]);

        $pengumuman->status = (int) $request->status;
        $pengumuman->save();

        return response()->json([
            'success' => true,
            'message' => 'Status updated',
            'status' => $pengumuman->status
        ]);
    }

    public function download(Pengumuman $pengumuman)
    {
        if (!$pengumuman->file_pengumuman) {
            return redirect()->back()->with('error', 'File tidak ditemukan.');
        }

        return Storage::download('public/pengumuman/' . $pengumuman->file_pengumuman);
    }
} 