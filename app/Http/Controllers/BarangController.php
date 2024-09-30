<?php

namespace App\Http\Controllers;

use App\Models\BarangModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Yajra\DataTables\DataTables;
use App\Models\KategoriModel;
use Illuminate\Database\QueryException;


class BarangController extends Controller
{
    // Jobsheet 5 //
    // Menampilkan halaman awal barang
    public function index()
    {
        $breadcrumb = (object) [
            'title' => 'Daftar Barang',
            'list' => ['Home', 'Barang']
        ];
        $page = (object) [
            'title' => 'Daftar barang yang terdaftar dalam sistem'
        ];

        $activeMenu = 'barang'; // set menu yang sedang aktif

        $kategori = KategoriModel::all();

        return view('barang.index', ['breadcrumb' => $breadcrumb, 'page' => $page, 'kategori' => $kategori, 'activeMenu' => $activeMenu]);
    }
    // Ambil data barang dalam bentuk json untuk datatables
    public function list(Request $request)
    {
        $barangs = BarangModel::select('barang_id','kategori_id','barang_kode','barang_nama','harga_beli','harga_jual')
            ->with('kategori');

            // Filter data barang berdasarkan kategori_id
            if($request->kategori_id){
                $barangs->where('kategori_id', $request->kategori_id);
            }

        return DataTables::of($barangs)
            // menambahkan kolom index / no urut (default nama kolom: DT_RowIndex)
            ->addIndexColumn()
            ->addColumn('aksi', function ($barang) { // menambahkan kolom aksi
                $btn = '<a href="' . url('/barang', $barang->barang_id) . '" class="btn btn-info btn-sm">Detail</a> ';
                $btn .= '<a href="' . url('barang/' . $barang->barang_id . '/edit') . '" class="btn btn-warning btn-sm">Edit</a> ';
                $btn .= '<form class="d-inline-block" method="POST" action="' . url('/barang', $barang->barang_id) . '">'
                    . csrf_field()
                    . method_field('DELETE')
                    . '<button type="submit" class="btn btn-danger btn-sm" onclick="return confirm(\'Apakah Anda yakin menghapus data ini?\');">Hapus</button></form>';
                return $btn;
            })
            ->rawColumns(['aksi']) // memberitahu bahwa kolom aksi adalah html
            ->make(true);
    }
    // Menampilkan halaman form tambah barang
    public function create()
    {
        // Breadcrumb untuk navigasi
        $breadcrumb = (object) [
            'title' => 'Tambah Barang',
            'list' => ['Home', 'Barang', 'Tambah']
        ];
        // Informasi halaman
        $page = (object) [
            'title' => 'Tambah barang baru'
        ];
        // Mengambil data kategori dari KategoriModel untuk ditampilkan di form
        $kategori = KategoriModel::all();
        // Menetapkan menu yang sedang aktif
        $activeMenu = 'barang';
        // Menampilkan view 'barang.create' dengan data yang sudah diambil
        return view('barang.create', [
            'breadcrumb' => $breadcrumb,
            'page' => $page,
            'kategori' => $kategori,
            'activeMenu' => $activeMenu
        ]);
    }
    // Menyimpan data barang baru
    public function store(Request $request)
    {
        // Validasi input
        $request->validate([
            // barang kode harus diisi, berupa string, minimal 3 karakter, dan bernilai unik di tabel m_barang kolom barangname
            'barang_kode' => 'required|string|max:10|unique:m_barang,barang_kode',
            'barang_nama' => 'required|string|max:100',
            'harga_beli' => 'required|integer',
            'harga_jual' => 'required|integer',
            'kategori_id' => 'required|integer',       // kategori_id harus diisi dan berupa angka
        ]);
        // Menyimpan data barang baru
        BarangModel::create([
            'barang_kode' => $request->barang_kode,
            'barang_nama' => $request->barang_nama,
            'harga_beli' => $request->harga_beli,
            'harga_jual' => $request->harga_jual,
            'kategori_id' => $request->kategori_id
        ]);
        // Redirect ke halaman /barang dengan pesan sukses
        return redirect('/barang')->with('success', 'Data barang berhasil disimpan');
    }
    // Menampilkan detail barang
    public function show(string $id)
    {
        // Mengambil data barang berdasarkan id dan relasi kategori
        $barang = BarangModel::with('kategori')->find($id);
        // Breadcrumb untuk navigasi
        $breadcrumb = (object) [
            'title' => 'Detail Barang',
            'list' => ['Home', 'Barang', 'Detail']
        ];
        // Informasi halaman
        $page = (object) [
            'title' => 'Detail barang'
        ];
        // Menetapkan menu yang sedang aktif
        $activeMenu = 'barang';
        // Menampilkan view 'barang.show' dengan data yang sudah diambil
        return view('barang.show', [
            'breadcrumb' => $breadcrumb,
            'page' => $page,
            'barang' => $barang,
            'activeMenu' => $activeMenu
        ]);
    }
    // Menampilkan halaman form edit barang
    public function edit(string $id)
    {
        $barang = BarangModel::find($id);       // Mengambil data barang berdasarkan id
        $kategori = KategoriModel::all();             // Mengambil semua data kategori untuk pilihan di form
        // Breadcrumb untuk navigasi
        $breadcrumb = (object) [
            'title' => 'Edit Barang',
            'list' => ['Home', 'Barang', 'Edit']
        ];
        // Informasi halaman
        $page = (object) [
            'title' => 'Edit barang'
        ];

        $activeMenu = 'barang';       // set menu yang sedang aktif
        // Menampilkan view 'barang.edit' dengan data yang sudah diambil
        return view('barang.edit', [
            'breadcrumb' => $breadcrumb,
            'page' => $page,
            'barang' => $barang,
            'kategori' => $kategori,
            'activeMenu' => $activeMenu
        ]);
    }
    // Menyimpan perubahan data barang
    public function update(Request $request, string $id)
    {
        // Validasi input
        $request->validate([
            // barangname harus diisi, berupa string, minimal 3 karakter, dan bernilai unik di tabel m_barang,
            // kecuali untuk barang dengan id yang sedang diedit
            'barang_kode' => 'required|string|max:10|unique:m_barang,barang_kode',
            'barang_nama' => 'required|string|max:100',
            'harga_beli' => 'required|integer',
            'harga_jual' => 'required|integer',
            'kategori_id' => 'required|integer',           // kategori_id harus diisi dan berupa angka
        ]);

        // Update data barang
        BarangModel::find($id)->update([
            'barang_kode' => $request->barang_kode,
            'barang_nama' => $request->barang_nama,
            'harga_beli' => $request->harga_beli,
            'harga_jual' => $request->harga_jual,
            'kategori_id' => $request->kategori_id
        ]);
        // Redirect ke halaman /barang dengan pesan sukses
        return redirect('/barang')->with('success', 'Data barang berhasil diubah');
    }
    // Menghapus data barang
    public function destroy(string $id)
    {
        // Cek apakah data barang dengan id yang dimaksud ada atau tidak
        $check = BarangModel::find($id);
        if (!$check) {
            // Jika data barang tidak ditemukan, kembalikan pesan error
            return redirect('/barang')->with('error', 'Data barang tidak ditemukan');
        }
        try {
            // Hapus data barang berdasarkan id
            BarangModel::destroy($id);
            // Jika berhasil, kembalikan pesan sukses
            return redirect('/barang')->with('success', 'Data barang berhasil dihapus');
        } catch (Illuminate\Database\QueryException $e) {
            // Jika terjadi error ketika menghapus data (misalnya ada data terkait di tabel lain)
            return redirect('/barang')->with('error', 'Data barang gagal dihapus karena masih terdapat tabel lain yang terkait dengan data ini');
        }
    }
}
