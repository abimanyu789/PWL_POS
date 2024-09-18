<?php

namespace App\Http\Controllers;

use App\Models\UserModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class UserController extends Controller
{
    public function index()
    {
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

        $user = UserModel::firstOrNew(
            [
                'username' => 'manager33',
                'nama' => 'Manager Tiga Tiga',
                'password' => Hash::make('12345'),
                'level_id' => 2
            ],
        );
        $user->save();
        return view('user', ['data' => $user]);
    }
}
