<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Yajra\DataTables\DataTables;
use App\Models\SupplierModel;
use Illuminate\Database\QueryException;

class SupplierController extends Controller
{

    // Js 5 Soal
    public function index()
    {
        $breadcrumb = (object) [
            'title' => 'Daftar Supplier',
            'list' => ['Home', 'Supplier']
        ];
        $page = (object) [
            'title' => 'Daftar Supplier yang terdaftar dalam sistem'
        ];

        $activeMenu = 'supplier'; // set menu yang sedang aktif

        $supplier = SupplierModel::all();

        return view('supplier.index', ['breadcrumb' => $breadcrumb, 'page' => $page, 'supplier' => $supplier, 'activeMenu' => $activeMenu]);
    }
    // Ambil data supplier dalam bentuk json untuk datatables
    public function list(Request $request)
    {
        $suppliers = SupplierModel::select('supplier_id', 'supplier_kode', 'supplier_nama','supplier_alamat');

        return DataTables::of($suppliers)
            // menambahkan kolom index / no urut (default nama kolom: DT_RowIndex)
            ->addIndexColumn()
            ->addColumn('aksi', function ($supplier) { // menambahkan kolom aksi
                $btn = '<a href="' . url('/supplier', $supplier->supplier_id) . '" class="btn btn-info btn-sm">Detail</a> ';
                $btn .= '<a href="' . url('supplier/' . $supplier->supplier_id . '/edit') . '" class="btn btn-warning btn-sm">Edit</a> ';
                $btn .= '<form class="d-inline-block" method="POST" action="' . url('/supplier', $supplier->supplier_id) . '">'
                    . csrf_field()
                    . method_field('DELETE')
                    . '<button type="submit" class="btn btn-danger btn-sm" onclick="return confirm(\'Apakah Anda yakin menghapus data ini?\');">Hapus</button></form>';
                return $btn;
            })
            ->rawColumns(['aksi']) // memberitahu bahwa kolom aksi adalah html
            ->make(true);
    }
    // Menampilkan halaman form tambah supplier
    public function create()
    {
        // Breadcrumb untuk navigasi
        $breadcrumb = (object) [
            'title' => 'Tambah Supplier',
            'list' => ['Home', 'Supplier', 'Tambah']
        ];
        // Informasi halaman
        $page = (object) [
            'title' => 'Tambah supplier baru'
        ];
        // Mengambil data supplier dari SupplierModel untuk ditampilkan di form
        $supplier = SupplierModel::all();
        // Menetapkan menu yang sedang aktif
        $activeMenu = 'supplier';
        // Menampilkan view 'supplier.create' dengan data yang sudah diambil
        return view('supplier.create', [
            'breadcrumb' => $breadcrumb,
            'page' => $page,
            'supplier' => $supplier,
            'activeMenu' => $activeMenu
        ]);
    }
    // Menyimpan data supplier baru
    public function store(Request $request)
    {
        // Validasi input
        $request->validate([
            // kode supplier harus diisi, berupa string, minimal 5 karakter, dan bernilai unik di tabel m_supplier kolom supplier kode
            'supplier_kode' => 'required|string|max:6|unique:m_supplier,supplier_kode',
            'supplier_nama' => 'required|string|max:100',
            'supplier_alamat' => 'required|string|max:200',

        ]);
        // Menyimpan data supplier baru
        SupplierModel::create([
            'supplier_kode' => $request->supplier_kode,
            'supplier_nama' => $request->supplier_nama,
            'supplier_alamat' => $request->supplier_alamat,
        ]);
        // Redirect ke halaman /supplier dengan pesan sukses
        return redirect('/supplier')->with('success', 'Data supplier berhasil disimpan');
    }
    // Menampilkan detail supplier
    public function show(string $id)
    {
        // Mengambil data supplier berdasarkan id dan relasi supplier
        $supplier = SupplierModel::find($id);
        // Breadcrumb untuk navigasi
        $breadcrumb = (object) [
            'title' => 'Detail Supplier',
            'list' => ['Home', 'Supplier', 'Detail']
        ];
        // Informasi halaman
        $page = (object) [
            'title' => 'Detail Supplier'
        ];
        // Menetapkan menu yang sedang aktif
        $activeMenu = 'supplier';
        // Menampilkan view 'supplier.show' dengan data yang sudah diambil
        return view('supplier.show', [
            'breadcrumb' => $breadcrumb,
            'page' => $page,
            'supplier' => $supplier,
            'activeMenu' => $activeMenu
        ]);
    }
    // Menampilkan halaman form edit supplier
    public function edit(string $id)
    {
        $supplier = SupplierModel::find($id);       // Mengambil data level berdasarkan id
        // Breadcrumb untuk navigasi
        $breadcrumb = (object) [
            'title' => 'Edit Supplier',
            'list' => ['Home', 'Supplier', 'Edit']
        ];
        // Informasi halaman
        $page = (object) [
            'title' => 'Edit Supplier'
        ];

        $activeMenu = 'supplier';       // set menu yang sedang aktif
        // Menampilkan view 'supplier.edit' dengan data yang sudah diambil
        return view('supplier.edit', [
            'breadcrumb' => $breadcrumb,
            'page' => $page,
            'supplier' => $supplier,
            'activeMenu' => $activeMenu
        ]);
    }
    // Menyimpan perubahan data supplier
    public function update(Request $request, string $id)
    {
        // Validasi input
        $request->validate([
            // kode_supplier harus diisi, berupa string, minimal 3 karakter, dan bernilai unik di tabel m_supplier,
            // kecuali untuk supplier dengan id yang sedang diedit
            'supplier_kode' => 'required|string|max:10|unique:m_supplier,supplier_kode',
            'supplier_nama' => 'required|string|max:100',
            'supplier_alamat' => 'required|string|max:225',
        ]);

        // Update data supplier
        SupplierModel::find($id)->update([
            'supplier_kode' => $request->supplier_kode,
            'supplier_nama' => $request->supplier_nama,
            'supplier_alamat' => $request->supplier_alamat,
        ]);
        // Redirect ke halaman /supplier dengan pesan sukses
        return redirect('/supplier')->with('success', 'Data supplier berhasil diubah');
    }
    // Menghapus data supplier
    public function destroy(string $id)
    {
        // Cek apakah data supplier dengan id yang dimaksud ada atau tidak
        $check = SupplierModel::find($id);
        if (!$check) {
            // Jika data supplier tidak ditemukan, kembalikan pesan error
            return redirect('/supplier')->with('error', 'Data supplier tidak ditemukan');
        }
        try {
            // Hapus data supplier berdasarkan id
            SupplierModel::destroy($id);
            // Jika berhasil, kembalikan pesan sukses
            return redirect('/supplier')->with('success', 'Data supplier berhasil dihapus');
        } catch (Illuminate\Database\QueryException $e) {
            // Jika terjadi error ketika menghapus data (misalnya ada data terkait di tabel lain)
            return redirect('/supplier')->with('error', 'Data supplier gagal dihapus karena masih terdapat tabel lain yang terkait dengan data ini');
        }
    }
}
