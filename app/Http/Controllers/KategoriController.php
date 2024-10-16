<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Yajra\DataTables\DataTables;
use App\Models\KategoriModel;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\Validator;

class KategoriController extends Controller
{
    // Js 5 Soal
    public function index()
    {
        $breadcrumb = (object) [
            'title' => 'Daftar Kategori',
            'list' => ['Home', 'Kategori']
        ];
        $page = (object) [
            'title' => 'Daftar Kategori yang terdaftar dalam sistem'
        ];

        $activeMenu = 'kategori'; // set menu yang sedang aktif

        $kategori = KategoriModel::all();

        return view('kategori.index', ['breadcrumb' => $breadcrumb, 'page' => $page, 'kategori' => $kategori, 'activeMenu' => $activeMenu]);
    }
    // Ambil data kategori dalam bentuk json untuk datatables
    public function list(Request $request)
    {
        $kategoris = KategoriModel::select('kategori_id', 'kategori_kode', 'kategori_nama');

        return DataTables::of($kategoris)
            // menambahkan kolom index / no urut (default nama kolom: DT_RowIndex)
            ->addIndexColumn()
            ->addColumn('aksi', function ($kategori) { // menambahkan kolom aksi
                // $btn = '<a href="' . url('/kategori', $kategori->kategori_id) . '" class="btn btn-info btn-sm">Detail</a> ';
                // $btn .= '<a href="' . url('kategori/' . $kategori->kategori_id . '/edit') . '" class="btn btn-warning btn-sm">Edit</a> ';
                // $btn .= '<form class="d-inline-block" method="POST" action="' . url('/kategori', $kategori->kategori_id) . '">'
                //     . csrf_field()
                //     . method_field('DELETE')
                //     . '<button type="submit" class="btn btn-danger btn-sm" onclick="return confirm(\'Apakah Anda yakin menghapus data ini?\');">Hapus</button></form>';
                $btn = '<button onclick="modalAction(\'' . url('/kategori/' . $kategori->kategori_id .
                    '/show_ajax') . '\')" class="btn btn-info btn-sm">Detail</button> ';
                $btn .= '<button onclick="modalAction(\'' . url('/kategori/' . $kategori->kategori_id .
                    '/edit_ajax') . '\')" class="btn btn-warning btn-sm">Edit</button> ';
                $btn .= '<button onclick="modalAction(\'' . url('/kategori/' . $kategori->kategori_id .
                    '/delete_ajax') . '\')" class="btn btn-danger btn-sm">Hapus</button> ';
                return $btn;
            })
            ->rawColumns(['aksi']) // memberitahu bahwa kolom aksi adalah html
            ->make(true);
    }
    // Menampilkan halaman form tambah kategori
    public function create()
    {
        // Breadcrumb untuk navigasi
        $breadcrumb = (object) [
            'title' => 'Tambah Kategori',
            'list' => ['Home', 'Kategori', 'Tambah']
        ];
        // Informasi halaman
        $page = (object) [
            'title' => 'Tambah kategori baru'
        ];
        // Mengambil data kategori dari KategoriModel untuk ditampilkan di form
        $kategori = KategoriModel::all();
        // Menetapkan menu yang sedang aktif
        $activeMenu = 'kategori';
        // Menampilkan view 'kategori.create' dengan data yang sudah diambil
        return view('kategori.create', [
            'breadcrumb' => $breadcrumb,
            'page' => $page,
            'kategori' => $kategori,
            'activeMenu' => $activeMenu
        ]);
    }
    // Menyimpan data kategori baru
    public function store(Request $request)
    {
        // Validasi input
        $request->validate([
            // kode kategori harus diisi, berupa string, minimal 5 karakter, dan bernilai unik di tabel m_kategori kolom kategori kode
            'kategori_kode' => 'required|string|max:5|unique:m_kategori,kategori_kode',
            'kategori_nama' => 'required|string|max:60',    // nama harus diisi, berupa string, dan maksimal 100 karakter
        ]);
        // Menyimpan data kategori baru
        KategoriModel::create([
            'kategori_kode' => $request->kategori_kode,
            'kategori_nama' => $request->kategori_nama,
        ]);
        // Redirect ke halaman /kategori dengan pesan sukses
        return redirect('/kategori')->with('success', 'Data kategori berhasil disimpan');
    }
    // Menampilkan detail kategori
    public function show(string $id)
    {
        // Mengambil data kategori berdasarkan id dan relasi kategori
        $kategori = KategoriModel::find($id);
        // Breadcrumb untuk navigasi
        $breadcrumb = (object) [
            'title' => 'Detail Kategori',
            'list' => ['Home', 'Kategori', 'Detail']
        ];
        // Informasi halaman
        $page = (object) [
            'title' => 'Detail kategori'
        ];
        // Menetapkan menu yang sedang aktif
        $activeMenu = 'kategori';
        // Menampilkan view 'kategori.show' dengan data yang sudah diambil
        return view('kategori.show', [
            'breadcrumb' => $breadcrumb,
            'page' => $page,
            'kategori' => $kategori,
            'activeMenu' => $activeMenu
        ]);
    }
    // Menampilkan halaman form edit kategori
    public function edit(string $id)
    {
        $kategori = KategoriModel::find($id);       // Mengambil data kategori berdasarkan id
        // Breadcrumb untuk navigasi
        $breadcrumb = (object) [
            'title' => 'Edit Kategori',
            'list' => ['Home', 'Kategori', 'Edit']
        ];
        // Informasi halaman
        $page = (object) [
            'title' => 'Edit Kategori'
        ];

        $activeMenu = 'kategori';       // set menu yang sedang aktif
        // Menampilkan view 'kategori.edit' dengan data yang sudah diambil
        return view('kategori.edit', [
            'breadcrumb' => $breadcrumb,
            'page' => $page,
            'kategori' => $kategori,
            'activeMenu' => $activeMenu
        ]);
    }
    // Menyimpan perubahan data kategori
    public function update(Request $request, string $id)
    {
        // Validasi input
        $request->validate([
            // kode_kategori harus diisi, berupa string, minimal 3 karakter, dan bernilai unik di tabel m_kategori,
            // kecuali untuk kategori dengan id yang sedang diedit
            'kategori_kode' => 'required|string|max:5|unique:m_kategori,kategori_kode',
            'kategori_nama' => 'required|string|max:60',    // nama harus diisi, berupa string, dan maksimal 60 karakter
        ]);

        // Update data kategori
        KategoriModel::find($id)->update([
            'kategori_kode' => $request->kategori_kode,
            'kategori_nama' => $request->kategori_nama,
        ]);
        // Redirect ke halaman /kategori dengan pesan sukses
        return redirect('/kategori')->with('success', 'Data kategori berhasil diubah');
    }
    // Menghapus data kategori
    public function destroy(string $id)
    {
        // Cek apakah data kategori dengan id yang dimaksud ada atau tidak
        $check = KategoriModel::find($id);
        if (!$check) {
            // Jika data kategori tidak ditemukan, kembalikan pesan error
            return redirect('/kategori')->with('error', 'Data kategori tidak ditemukan');
        }
        try {
            // Hapus data kategori berdasarkan id
            KategoriModel::destroy($id);
            // Jika berhasil, kembalikan pesan sukses
            return redirect('/kategori')->with('success', 'Data kategori berhasil dihapus');
        } catch (Illuminate\Database\QueryException $e) {
            // Jika terjadi error ketika menghapus data (misalnya ada data terkait di tabel lain)
            return redirect('/kategori')->with('error', 'Data kategori gagal dihapus karena masih terdapat tabel lain yang terkait dengan data ini');
        }
    }
    // Jobsheet  6
    public function create_ajax()
    {
        $kategori = KategoriModel::all();
        return view('kategori.create_ajax');
    }
    public function store_ajax(Request $request)
    {
        // Cek apakah request berupa ajax atau ingin JSON
        if ($request->ajax() || $request->wantsJson()) {
            $rules = [
                'kategori_kode' => 'required|max:20|unique:m_kategori,kategori_kode',
                'kategori_nama' => 'required|max:100'
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
            // Simpan data kategori
            KategoriModel::create($request->all());

            // Jika berhasil
            return response()->json([
                'status' => true,
                'message' => 'Data kategori berhasil disimpan',
            ]);
        }
        // Redirect jika bukan request Ajax
        return redirect('/');
    }
    // Menampilkan halaman form edit kategori ajax
    public function edit_ajax(string $id)
    {
        $kategori = KategoriModel::find($id);
        return view('kategori.edit_ajax', ['kategori' => $kategori]);
    }
    public function update_ajax(Request $request, $id)
    {
        // cek apakah request dari ajax
        if ($request->ajax() || $request->wantsJson()) {
            $rules = [
                'kategori_kode' => 'required|max:20|unique:m_kategori,kategori_kode',
                'kategori_nama' => 'required|max:100'
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
            $check = KategoriModel::find($id);
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
        $kategori = KategoriModel::find($id);
        return view('kategori.confirm_ajax', ['kategori' => $kategori]);
    }
    public function delete_ajax(Request $request, $id)
    {
        // cek apakah request dari ajax
        if ($request->ajax() || $request->wantsJson()) {
            $kategori = KategoriModel::find($id);
            if ($kategori) {
                $kategori->delete();
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
        $kategori = KategoriModel::find($id);
        return view('kategori.show_ajax', ['kategori' => $kategori]);
    }
}
