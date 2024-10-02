<?php

namespace App\Http\Controllers;

use App\Models\UserModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Yajra\DataTables\DataTables;
use App\Models\LevelModel;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\Validator;


class UserController extends Controller
{

    // public function index()    {
    // Tambah data user dengan Eloquent Model
    // $data = [
    //     'nama' => 'Pelanggan Pertama',
    // ];
    // Update data user dengan username 'customer-1'
    // UserModel::where('username', 'customer-1')->update($data);
    // Coba akses model UserModel

    // $data = [
    //     'level_id' => 2,
    //     'username' => 'manager_tiga',
    //     'nama' => 'Manager 3',
    //     'password' => Hash::make('12345')
    // ];
    // UserModel::create($data);
    // $user = UserModel::all(); // Ambil semua data dari tabel m_user

    // $user = UserModel::findOr(1,['username', 'nama'],function () {
    //     abort(404);
    // });
    // $user = UserModel::where('level_id',2)->count();
    // dd($user);

    // $user = UserModel::firstOrNew(
    //     [
    //         'username' => 'manager11',
    //         'nama' => 'Manager11',
    //         'password' => Hash::make('12345'),
    //         'level_id' => 2
    //     ],
    // );
    // $user->username = 'manager12';

    // $user->save();

    // $user->wasChanged(); // true
    // $user->wasChanged('username'); // true
    // $user->wasChanged(['username', 'level_id']); // true
    // $user->wasChanged('name'); // false
    // dd($user->wasChanged(['nama', 'username'])); // true

    // $user->isDirty(); // true
    // $user->isDirty('username'); // true
    // $user->isDirty('nama'); // false
    // $user->isDirty(['nama', 'username']); // true

    // $user->isClean(); // false
    // $user->isClean('username'); // false
    // $user->isClean('nama'); // true
    // $user->isClean(['nama', 'username']); // false

    // $user->save();

    // $user->isDirty(); // false
    // $user->isClean(); // true
    // dd($user->isDirty());
    // return view('user', ['data' => $user]);

    // $user = UserModel::all();
    // return view('user', ['data' => $user]);
    // }

    // Jobsheet 4
    public function tambah()
    {
        return view('user_tambah');
    }
    public function tambah_simpan(Request $request)
    {
        UserModel::create([
            'username' => $request->username,
            'nama' => $request->nama,
            'password' => Hash::make('$request->password'),
            'level_id' => $request->level_id
        ]);
        return redirect('/user');
    }
    public function ubah($id)
    {
        $user = UserModel::find($id);
        return view('user_ubah', ['data' => $user]);
    }
    public function ubah_simpan($id, Request $request)
    {
        // Temukan user berdasarkan ID
        $user = UserModel::find($id);
        // Update data user
        $user->username = $request->username;
        $user->nama = $request->nama;
        $user->password = Hash::make($request->password);
        $user->level_id = $request->level_id;
        // Simpan perubahan
        $user->save();
        // Redirect ke halaman user
        return redirect('/user');
    }
    public function hapus($id)
    {
        $user = UserModel::find($id);
        $user->delete();

        return redirect('/user');
    }

    // Jobsheet 5 //
    // Menampilkan halaman awal user
    public function index()
    {
        $breadcrumb = (object) [
            'title' => 'Daftar User',
            'list' => ['Home', 'User']
        ];
        $page = (object) [
            'title' => 'Daftar user yang terdaftar dalam sistem'
        ];

        $activeMenu = 'user'; // set menu yang sedang aktif

        $level = LevelModel::all();

        return view('user.index', ['breadcrumb' => $breadcrumb, 'page' => $page, 'level' => $level, 'activeMenu' => $activeMenu]);
    }
    // Ambil data user dalam bentuk json untuk datatables
    public function list(Request $request)
    {
        $users = UserModel::select('user_id', 'username', 'nama', 'level_id')
            ->with('level');

        // Filter data user berdasarkan level_id
        if ($request->level_id) {
            $users->where('level_id', $request->level_id);
        }

        return DataTables::of($users)
            // menambahkan kolom index / no urut (default nama kolom: DT_RowIndex)
            ->addIndexColumn()
            ->addColumn('aksi', function ($user) { // menambahkan kolom aksi
                $btn = '<a href="' . url('/user', $user->user_id) . '" class="btn btn-info btn-sm">Detail</a> ';
                $btn .= '<a href="' . url('user/' . $user->user_id . '/edit') . '" class="btn btn-warning btn-sm">Edit</a> ';
                $btn .= '<form class="d-inline-block" method="POST" action="' . url('/user', $user->user_id) . '">'
                    . csrf_field()
                    . method_field('DELETE')
                    . '<button type="submit" class="btn btn-danger btn-sm" onclick="return confirm(\'Apakah Anda yakin menghapus data ini?\');">Hapus</button></form>';
                return $btn;
            })
            ->rawColumns(['aksi']) // memberitahu bahwa kolom aksi adalah html
            ->make(true);
    }
    // Menampilkan halaman form tambah user
    public function create()
    {
        // Breadcrumb untuk navigasi
        $breadcrumb = (object) [
            'title' => 'Tambah User',
            'list' => ['Home', 'User', 'Tambah']
        ];
        // Informasi halaman
        $page = (object) [
            'title' => 'Tambah user baru'
        ];
        // Mengambil data level dari LevelModel untuk ditampilkan di form
        $level = LevelModel::all();
        // Menetapkan menu yang sedang aktif
        $activeMenu = 'user';
        // Menampilkan view 'user.create' dengan data yang sudah diambil
        return view('user.create', [
            'breadcrumb' => $breadcrumb,
            'page' => $page,
            'level' => $level,
            'activeMenu' => $activeMenu
        ]);
    }
    // Menyimpan data user baru
    public function store(Request $request)
    {
        // Validasi input
        $request->validate([
            // username harus diisi, berupa string, minimal 3 karakter, dan bernilai unik di tabel m_user kolom username
            'username' => 'required|string|min:3|unique:m_user,username',
            'nama' => 'required|string|max:100',    // nama harus diisi, berupa string, dan maksimal 100 karakter
            'password' => 'required|min:5',         // password harus diisi dan minimal 5 karakter
            'level_id' => 'required|integer',       // level_id harus diisi dan berupa angka
        ]);
        // Menyimpan data user baru
        UserModel::create([
            'username' => $request->username,
            'nama' => $request->nama,
            'password' => bcrypt($request->password),       // Password dienkripsi sebelum disimpan
            'level_id' => $request->level_id
        ]);
        // Redirect ke halaman /user dengan pesan sukses
        return redirect('/user')->with('success', 'Data user berhasil disimpan');
    }
    // Menampilkan detail user
    public function show(string $id)
    {
        // Mengambil data user berdasarkan id dan relasi level
        $user = UserModel::with('level')->find($id);
        // Breadcrumb untuk navigasi
        $breadcrumb = (object) [
            'title' => 'Detail User',
            'list' => ['Home', 'User', 'Detail']
        ];
        // Informasi halaman
        $page = (object) [
            'title' => 'Detail user'
        ];
        // Menetapkan menu yang sedang aktif
        $activeMenu = 'user';
        // Menampilkan view 'user.show' dengan data yang sudah diambil
        return view('user.show', [
            'breadcrumb' => $breadcrumb,
            'page' => $page,
            'user' => $user,
            'activeMenu' => $activeMenu
        ]);
    }
    // Menampilkan halaman form edit user
    public function edit(string $id)
    {
        $user = UserModel::find($id);       // Mengambil data user berdasarkan id
        $level = LevelModel::all();             // Mengambil semua data level untuk pilihan di form
        // Breadcrumb untuk navigasi
        $breadcrumb = (object) [
            'title' => 'Edit User',
            'list' => ['Home', 'User', 'Edit']
        ];
        // Informasi halaman
        $page = (object) [
            'title' => 'Edit user'
        ];

        $activeMenu = 'user';       // set menu yang sedang aktif
        // Menampilkan view 'user.edit' dengan data yang sudah diambil
        return view('user.edit', [
            'breadcrumb' => $breadcrumb,
            'page' => $page,
            'user' => $user,
            'level' => $level,
            'activeMenu' => $activeMenu
        ]);
    }
    // Menyimpan perubahan data user
    public function update(Request $request, string $id)
    {
        // Validasi input
        $request->validate([
            // username harus diisi, berupa string, minimal 3 karakter, dan bernilai unik di tabel m_user,
            // kecuali untuk user dengan id yang sedang diedit
            'username' => 'required|string|min:3|unique:m_user,username,' . $id . ',user_id',
            'nama' => 'required|string|max:100',        // nama harus diisi, berupa string, dan maksimal 100 karakter
            'password' => 'nullable|min:5',             // password bisa diisi (minimal 5 karakter) dan bisa tidak diisi
            'level_id' => 'required|integer',           // level_id harus diisi dan berupa angka
        ]);

        // Update data user
        UserModel::find($id)->update([
            'username' => $request->username,
            'nama' => $request->nama,
            // Jika password diisi, enkripsi dan simpan. Jika tidak, gunakan password lama.
            'password' => $request->password ? bcrypt($request->password) : UserModel::find($id)->password,
            'level_id' => $request->level_id
        ]);
        // Redirect ke halaman /user dengan pesan sukses
        return redirect('/user')->with('success', 'Data user berhasil diubah');
    }
    // Menghapus data user
    public function destroy(string $id)
    {
        // Cek apakah data user dengan id yang dimaksud ada atau tidak
        $check = UserModel::find($id);
        if (!$check) {
            // Jika data user tidak ditemukan, kembalikan pesan error
            return redirect('/user')->with('error', 'Data user tidak ditemukan');
        }
        try {
            // Hapus data user berdasarkan id
            UserModel::destroy($id);
            // Jika berhasil, kembalikan pesan sukses
            return redirect('/user')->with('success', 'Data user berhasil dihapus');
        } catch (Illuminate\Database\QueryException $e) {
            // Jika terjadi error ketika menghapus data (misalnya ada data terkait di tabel lain)
            return redirect('/user')->with('error', 'Data user gagal dihapus karena masih terdapat tabel lain yang terkait dengan data ini');
        }
    }
    // Jobsheet  6
    public function create_ajax()
    {
        $level = LevelModel::select('level_id', 'level_nama')->get();
        return view('user.create_ajax')
                ->with('level', $level);
    }
    public function store_ajax(Request $request) {
        // Cek apakah request berupa ajax atau ingin JSON
        if ($request->ajax() || $request->wantsJson()) {
            $rules = [
                'level_id' => 'required|integer',
                'username' => 'required|string|min:3|unique:m_user,username',
                'nama' => 'required|string|max:100',
                'password' => 'required|min:6'
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
            // Simpan data user
            UserModel::create($request->all());

            // Jika berhasil
            return response()->json([
                'status' => true,
                'message' => 'Data user berhasil disimpan',
            ]);
        }
        // Redirect jika bukan request Ajax
        return redirect('/');
    }

}
