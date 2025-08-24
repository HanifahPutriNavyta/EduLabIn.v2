<?php

namespace App\Http\Controllers;

use App\Models\Dosen;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DataDosenController extends Controller
{
    //

    public function index()
    {
        // Ambil semua dosen beserta user, profil, dan kelas praktikumnya (beserta mata kuliah)
        $dosenList = \App\Models\Dosen::with([
            'user.profil',
            'kelasPraktikums.mataKuliah'
        ])->get();

        // Siapkan data: satu baris per kelas praktikum yang diampu dosen
        $data = [];
        foreach ($dosenList as $dosen) {
            $profil = $dosen->user->profil ?? null;
            foreach ($dosen->kelasPraktikums as $kelas) {
                $matkul = $kelas->mataKuliah->nama_mk ?? '-';
                $data[] = [
                    'nip'        => $profil->no_identitas ?? '-',
                    'nama'       => $profil->nama_lengkap ?? $dosen->user->username ?? '-',
                    'email'      => $dosen->user->email ?? '-',
                    'kelas'      => $matkul . ' (' . $kelas->kode_kelas . ')',
                    'prodi'      => $profil->program_studi ?? '-',
                    'fakultas'   => $profil->fakultas ?? '-',
                    'departemen' => $profil->departemen ?? '-',
                ];
            }
            // Jika dosen belum punya kelas, tetap tampilkan datanya
            if ($dosen->kelasPraktikums->isEmpty()) {
                $data[] = [
                    'nip'        => $profil->no_identitas ?? '-',
                    'nama'       => $profil->nama_lengkap ?? $dosen->user->username ?? '-',
                    'email'      => $dosen->user->email ?? '-',
                    'kelas'      => '-',
                    'prodi'      => $profil->program_studi ?? '-',
                    'fakultas'   => $profil->fakultas ?? '-',
                    'departemen' => $profil->departemen ?? '-',
                ];
            }
        }

        $columns = [
            ['key' => 'nip', 'label' => 'NIP'],
            ['key' => 'nama', 'label' => 'Nama'],
            ['key' => 'email', 'label' => 'Email'],
            ['key' => 'kelas', 'label' => 'Mata Kuliah Praktikum'],
            ['key' => 'prodi', 'label' => 'Prodi'],
            ['key' => 'fakultas', 'label' => 'Fakultas'],
            ['key' => 'departemen', 'label' => 'Departemen'],
        ];

        return view('data-dosen.index', [
            'columns' => $columns,
            'data' => $data,
        ]);
    }


    public function create()
    {
        // Logic to show the form for creating a new dosen
        $mataKuliahs = \App\Models\MataKuliah::all();
        $kelasList = \App\Models\KelasPraktikum::all();
        return view('data-dosen.CreateDosenData', compact('mataKuliahs', 'kelasList'));
    }

    public function store(Request $request)
    {
        // Trial and error approach for storing a new dosen

        // Step 1: Validasi input
        $validatedData = $request->validate([
            'nama' => 'required|string|max:255',
            'nidn' => 'required|string|max:20',
            'mata_kuliah_id' => 'required|exists:mata_kuliahs,mk_id',
            'kelas_id' => 'required|exists:kelas_praktikums,kelas_id',
            'email' => 'required|email|max:255|unique:users,email',
        ]);

        // Step 2: Cek NIDN sudah ada atau belum
        $existingDosen = \App\Models\Dosen::whereHas('user', function($query) use ($request) {
            $query->where('email', $request->email);
        })->first();

        if ($existingDosen) {
            return response()->json(['error' => 'NIDN already exists.'], 422);
        }
        DB::beginTransaction();
        // Step 3: Coba buat user dosen
        try {
            $user_dosen = \App\Models\User::create([
                'username' => explode(' ', trim($request->nama))[0],
                'email' => $request->email,
                'password' => bcrypt($request->nidn),
                'role_id' => 3,
            ]);
            $dosen = Dosen::create([
                'user_id' => $user_dosen->user_id,
            ]);

            $kelas = \App\Models\KelasPraktikum::where('kelas_id', $request->kelas_id)->first();
            if (!$kelas) {
                return response()->json(['error' => 'Kelas not found.'], 404);
            }
            $kelas->dosen_id = $dosen->dosen_id;
            $kelas->save();

            // Buat profil pengguna
            $profil = new \App\Models\ProfilPengguna();
            $profil->user_id = $user_dosen->user_id;
            $profil->nama_lengkap = $request->nama;
            $profil->no_identitas = $request->nidn;
            $profil->fakultas = $request->fakultas;
            $profil->program_studi = $request->program_studi;
            $profil->departemen = $request->departemen;
            $profil->save();    


            if($request->hasFile('foto')) {
                $file = $request->file('foto');
                $filename = time() . '_' . $file->getClientOriginalName();
                $file->storeAs('foto_dosen', $filename, 'public');
                $profil->foto_path = 'foto_dosen/' . $filename;
                $profil->save();
            }

            // Commit transaksi jika berhasil
            DB::commit();
            return response()->json(['success' => 'Dosen created successfully.'], 201);
        } catch (\Exception $e) {
            // Jika gagal, kembalikan response error
            DB::rollBack();
            return response()->json(['error' => 'Gagal membuat user dosen: ' . $e->getMessage()], 500);
        }

        // Step 4: Coba buat data dosen
        try {
            $dosen = new \App\Models\Dosen();
            $dosen->user_id = $user_dosen->id;
            $dosen->mata_kuliah_id = $request->mata_kuliah_id;
            $dosen->kelas_id = $request->kelas_id;
            $dosen->save();
        } catch (\Exception $e) {
            // Jika gagal, hapus user yang sudah dibuat
            $user_dosen->delete();
            return response()->json(['error' => 'Gagal menyimpan data dosen: ' . $e->getMessage()], 500);
        }

        // Step 5: Berhasil
        return response()->json(['success' => 'Dosen created successfully.'], 201);
    }
}
