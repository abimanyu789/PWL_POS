<?php
namespace App\Http\Controllers;
use App\Models\LevelModel;
use App\Models\UserModel;
use Illuminate\Http\Request;
class RegistrasiController extends Controller
{
    // Menampilkan halaman form tambah user
    public function Registrasi()
    {
        $level = LevelModel::select('level_id', 'level_nama')->get();
        return view('auth.registrasi')
                    ->with('level', $level);
    }
    // Menyimpan data user baru
    public function store(Request $request)
    {
        $request->validate([
            // username harus diisi, berupa string, minimal 3 karakter, dan bernilai unik di tabel m_user kolom username
            'username'  => 'required|string|min:4|unique:m_user,username',
            'nama'      => 'required|string|max:100',   // nama harus diisi, berupa string, dan maksimal 100 karakter
            'password'  => 'required|min:6',            // password harus diisi dan minimal 6 karakter
            'level_id'  => 'required|integer'           // level_id harus diisi dan berupa angka
        ]);
        UserModel::create([
            'username'  => $request->username,
            'nama'      => $request->nama,
            'password'  => bcrypt($request->password), // password dienkripsi sebelum disimpan
            'level_id'  => $request->level_id
        ]);
        return redirect('/login')->with('success', 'Registrasi berhasil silahkan login');
    }
}
