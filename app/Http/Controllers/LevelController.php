<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Yajra\DataTables\DataTables;
use App\Models\LevelModel;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\Validator;

class LevelController extends Controller
{
    //     public function index()
    // {
    //     // DB::insert('insert into m_level (level_kode, level_nama, created_at) values (?, ?, ?)',
    //     // ['CUS', 'Pelanggan', now()]);
    //     // return "Insert data baru berhasil";

    //     // $row = DB::update('update m_level set level_nama = ? where level_kode = ?', ['Customer', 'CUS']);
    //     // return 'Update data berhasil. Jumlah data yang diupdate: ' . $row . ' baris';

    //     // $row = DB::delete('delete from m_level where level_kode = ?', ['cus']);
    //     // return 'Delete data berhasil. Jumlah data yang dihapus: ' . $row . ' baris';

    //     // $data = DB::select('select * from m_level');
    //     // return view('level', ['data' => $data]);

    // }
    // Js 5 Soal
    public function index()
    {
        $breadcrumb = (object) [
            'title' => 'Daftar Level',
            'list' => ['Home', 'Level']
        ];
        $page = (object) [
            'title' => 'Daftar Level yang terdaftar dalam sistem'
        ];

        $activeMenu = 'level'; // set menu yang sedang aktif

        $level = LevelModel::all();

        return view('level.index', ['breadcrumb' => $breadcrumb, 'page' => $page, 'level' => $level, 'activeMenu' => $activeMenu]);
    }
    // Ambil data level dalam bentuk json untuk datatables
    public function list(Request $request)
    {
        $levels = LevelModel::select('level_id', 'level_kode', 'level_nama');

        return DataTables::of($levels)
            // menambahkan kolom index / no urut (default nama kolom: DT_RowIndex)
            ->addIndexColumn()
            ->addColumn('aksi', function ($level) { // menambahkan kolom aksi
                // $btn = '<a href="' . url('/level', $level->level_id) . '" class="btn btn-info btn-sm">Detail</a> ';
                // $btn .= '<a href="' . url('level/' . $level->level_id . '/edit') . '" class="btn btn-warning btn-sm">Edit</a> ';
                // $btn .= '<form class="d-inline-block" method="POST" action="' . url('/level', $level->level_id) . '">'
                //     . csrf_field()
                //     . method_field('DELETE')
                //     . '<button type="submit" class="btn btn-danger btn-sm" onclick="return confirm(\'Apakah Anda yakin menghapus data ini?\');">Hapus</button></form>';
                $btn = '<button onclick="modalAction(\'' . url('/level/' . $level->level_id .
                    '/show_ajax') . '\')" class="btn btn-info btn-sm">Detail</button> ';
                $btn .= '<button onclick="modalAction(\'' . url('/level/' . $level->level_id .
                    '/edit_ajax') . '\')" class="btn btn-warning btn-sm">Edit</button> ';
                $btn .= '<button onclick="modalAction(\'' . url('/level/' . $level->level_id .
                    '/delete_ajax') . '\')" class="btn btn-danger btn-sm">Hapus</button> ';
                return $btn;
            })
            ->rawColumns(['aksi']) // memberitahu bahwa kolom aksi adalah html
            ->make(true);
    }
    // Menampilkan halaman form tambah level
    public function create()
    {
        // Breadcrumb untuk navigasi
        $breadcrumb = (object) [
            'title' => 'Tambah Level',
            'list' => ['Home', 'Level', 'Tambah']
        ];
        // Informasi halaman
        $page = (object) [
            'title' => 'Tambah level baru'
        ];
        // Mengambil data level dari LevelModel untuk ditampilkan di form
        $level = LevelModel::all();
        // Menetapkan menu yang sedang aktif
        $activeMenu = 'level';
        // Menampilkan view 'level.create' dengan data yang sudah diambil
        return view('level.create', [
            'breadcrumb' => $breadcrumb,
            'page' => $page,
            'level' => $level,
            'activeMenu' => $activeMenu
        ]);
    }
    // Menyimpan data level baru
    public function store(Request $request)
    {
        // Validasi input
        $request->validate([
            // kode level harus diisi, berupa string, minimal 3 karakter, dan bernilai unik di tabel m_level kolom levelname
            'level_kode' => 'required|string|max:3|unique:m_level,level_kode',
            'level_nama' => 'required|string|max:60',    // nama harus diisi, berupa string, dan maksimal 100 karakter
        ]);
        // Menyimpan data level baru
        LevelModel::create([
            'level_kode' => $request->level_kode,
            'level_nama' => $request->level_nama,
        ]);
        // Redirect ke halaman /level dengan pesan sukses
        return redirect('/level')->with('success', 'Data level berhasil disimpan');
    }
    // Menampilkan detail level
    public function show(string $id)
    {
        // Mengambil data level berdasarkan id dan relasi level
        $level = LevelModel::find($id);
        // Breadcrumb untuk navigasi
        $breadcrumb = (object) [
            'title' => 'Detail Level',
            'list' => ['Home', 'Level', 'Detail']
        ];
        // Informasi halaman
        $page = (object) [
            'title' => 'Detail level'
        ];
        // Menetapkan menu yang sedang aktif
        $activeMenu = 'level';
        // Menampilkan view 'level.show' dengan data yang sudah diambil
        return view('level.show', [
            'breadcrumb' => $breadcrumb,
            'page' => $page,
            'level' => $level,
            'activeMenu' => $activeMenu
        ]);
    }
    // Menampilkan halaman form edit level
    public function edit(string $id)
    {
        $level = LevelModel::find($id);       // Mengambil data level berdasarkan id
        // Breadcrumb untuk navigasi
        $breadcrumb = (object) [
            'title' => 'Edit Level',
            'list' => ['Home', 'Level', 'Edit']
        ];
        // Informasi halaman
        $page = (object) [
            'title' => 'Edit Level'
        ];

        $activeMenu = 'level';       // set menu yang sedang aktif
        // Menampilkan view 'level.edit' dengan data yang sudah diambil
        return view('level.edit', [
            'breadcrumb' => $breadcrumb,
            'page' => $page,
            'level' => $level,
            'activeMenu' => $activeMenu
        ]);
    }
    // Menyimpan perubahan data level
    public function update(Request $request, string $id)
    {
        // Validasi input
        $request->validate([
            // kode_level harus diisi, berupa string, minimal 3 karakter, dan bernilai unik di tabel m_level,
            // kecuali untuk level dengan id yang sedang diedit
            'level_kode' => 'required|string|max:3|unique:m_level,level_kode',
            'level_nama' => 'required|string|max:60',    // nama harus diisi, berupa string, dan maksimal 60 karakter
        ]);

        // Update data level
        LevelModel::find($id)->update([
            'level_kode' => $request->level_kode,
            'level_nama' => $request->level_nama,
        ]);
        // Redirect ke halaman /level dengan pesan sukses
        return redirect('/level')->with('success', 'Data level berhasil diubah');
    }
    // Menghapus data level
    public function destroy(string $id)
    {
        // Cek apakah data level dengan id yang dimaksud ada atau tidak
        $check = LevelModel::find($id);
        if (!$check) {
            // Jika data level tidak ditemukan, kembalikan pesan error
            return redirect('/level')->with('error', 'Data level tidak ditemukan');
        }
        try {
            // Hapus data level berdasarkan id
            LevelModel::destroy($id);
            // Jika berhasil, kembalikan pesan sukses
            return redirect('/level')->with('success', 'Data level berhasil dihapus');
        } catch (Illuminate\Database\QueryException $e) {
            // Jika terjadi error ketika menghapus data (misalnya ada data terkait di tabel lain)
            return redirect('/level')->with('error', 'Data level gagal dihapus karena masih terdapat tabel lain yang terkait dengan data ini');
        }
    }
    // Jobsheet  6
    public function create_ajax()
    {
        $level = LevelModel::all();
        return view('level.create_ajax');
    }
    public function store_ajax(Request $request)
    {
        // Cek apakah request berupa ajax atau ingin JSON
        if ($request->ajax() || $request->wantsJson()) {
            $rules = [
                'level_kode' => 'required|string|max:10|unique:m_level,level_kode',
                'level_nama' => 'required|string|max:100'
            ];

            // Gunakan Validator dari Illuminate\Support\Facades\Validator;
            $validator = Validator::make($request->all(), $rules);
            // Jika validasi gagal
            if ($validator->fails()) {
                return response()->json([
                    'status' => false, // response status, false: error/gagal, true: berhasil
                    'message' => 'Validasi Gagal',
                    'msgField' => $validator->errors(), // pesan error validasi
                ]);
            }
            // Simpan data level
            LevelModel::create($request->all());

            // Jika berhasil
            return response()->json([
                'status' => true,
                'message' => 'Data level berhasil disimpan',
            ]);
        }
        // Redirect jika bukan request Ajax
        return redirect('/');
    }
    // Menampilkan halaman form edit level ajax
    public function edit_ajax(string $id)
    {
        $level = LevelModel::find($id);
        return view('level.edit_ajax', ['level' => $level]);
    }
    public function update_ajax(Request $request, $id)
    {
        // cek apakah request dari ajax
        if ($request->ajax() || $request->wantsJson()) {
            $rules = [
                'level_kode' => 'required|max:20|unique:m_level,level_kode,' . $id . ',level_id',
                'level_nama' => 'required|max:100'
            ];
            // use Illuminate\Support\Facades\Validator;
            $validator = Validator::make($request->all(), $rules);
            if ($validator->fails()) {
                return response()->json([
                    'status' => false, // respon json, true: berhasil, false: gagal
                    'message' => 'Validasi gagal.',
                    'msgField' => $validator->errors() // menunjukkan field mana yang error
                ]);
            }
            $check = LevelModel::find($id);
            if ($check) {
                if (!$request->filled('password')) { // jika password tidak diisi, maka hapus dari request
                    $request->request->remove('password');
                }
                $check->update($request->all());
                return response()->json([
                    'status' => true,
                    'message' => 'Data berhasil diupdate'
                ]);
            } else {
                return response()->json([
                    'status' => false,
                    'message' => 'Data tidak ditemukan'
                ]);
            }
        }
        return redirect('/');
    }
    public function confirm_ajax(string $id)
    {
        $level = LevelModel::find($id);
        return view('level.confirm_ajax', ['level' => $level]);
    }
    public function delete_ajax(Request $request, $id)
    {
        // cek apakah request dari ajax
        if ($request->ajax() || $request->wantsJson()) {
            $level = LevelModel::find($id);
            if ($level) {
                $level->delete();
                return response()->json([
                    'status' => true,
                    'message' => 'Data berhasil dihapus'
                ]);
            } else {
                return response()->json([
                    'status' => false,
                    'message' => 'Data tidak ditemukan'
                ]);
            }
        }
        return redirect('/');
    }
    public function show_ajax(string $id)
    {
        $level = LevelModel::find($id);
        return view('level.show_ajax', ['level' => $level]);
    }
}
