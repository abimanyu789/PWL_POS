<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Yajra\DataTables\DataTables;
use App\Models\KategoriModel;
use Illuminate\Database\QueryException;

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
                $btn = '<a href="' . url('/kategori', $kategori->kategori_id) . '" class="btn btn-info btn-sm">Detail</a> ';
                $btn .= '<a href="' . url('kategori/' . $kategori->kategori_id . '/edit') . '" class="btn btn-warning btn-sm">Edit</a> ';
                $btn .= '<form class="d-inline-block" method="POST" action="' . url('/kategori', $kategori->kategori_id) . '">'
                    . csrf_field()
                    . method_field('DELETE')
                    . '<button type="submit" class="btn btn-danger btn-sm" onclick="return confirm(\'Apakah Anda yakin menghapus data ini?\');">Hapus</button></form>';
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
        $kategori = KategoriModel::find($id);       // Mengambil data level berdasarkan id
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
}
