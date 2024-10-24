<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\LevelController;
use App\Http\Controllers\KategoriController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\SupplierController;
use App\Http\Controllers\BarangController;
use App\Http\Controllers\WelcomeController;
use App\Http\Controllers\RegistrasiController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\StokController;
use App\Http\Controllers\PenjualanController;
use Illuminate\Support\Facades\Hash;

// Jobsheet 7
Route::pattern('id', '[0-9]+'); // arti: ketika ada parameter {id}, maka harus berupa angka

Route::get('registrasi', [RegistrasiController::class, 'registrasi'])->name('registrasi');
Route::post('registrasi', [RegistrasiController::class, 'store']);

Route::get('login', [AuthController::class, 'login'])->name('login');
Route::post('login', [AuthController::class, 'postlogin']);
Route::get('logout', [AuthController::class, 'logout'])->middleware('auth');
Route::get('register', [AuthController::class, 'showRegisterForm'])->name('register');
Route::post('register', [AuthController::class, 'register']);

Route::middleware(['auth'])->group(function () {  // arti: semua route di dalam group ini harus login dulu
    Route::get('/', [WelcomeController::class, 'index']);
    // route level
    Route::get('/', function () {
        return view('welcome');
    });
    Route::group(['prefix' => 'profile'], function () {
        Route::get('/', [ProfileController::class, 'index'])->name('profil.index');
        Route::patch('/{id}', [ProfileController::class, 'update'])->name('profil.update');
    });

    // Jobsheet 5 & Jobsheet 6 Ajax //
    Route::get('/', [WelcomeController::class, 'index']);
    // table user
    Route::group(['prefix' => 'user', 'middleware' =>  ['authorize:ADM,MNG']], function () {
        Route::get('/', [UserController::class, 'index']);                  // halaman awal user
        Route::post('/list', [UserController::class, 'list']);              // data user dalam bentuk json untuk datatables
        Route::get('/create', [UserController::class, 'create']);           // halaman form tambah user
        Route::post('/', [UserController::class, 'store']);                 // menyimpan data user baru
        Route::get('/{id}', [UserController::class, 'show']);               // detail user
        Route::get('/{id}/edit', [UserController::class, 'edit']);          // halaman form edit user
        Route::put('/{id}', [UserController::class, 'update']);             // menyimpan perubahan data user
        Route::get('/create_ajax', [UserController::class, 'create_ajax']); // halaman form tambah user Ajax
        Route::get('/{id}/edit_ajax', [UserController::class, 'edit_ajax']); // halaman form edit user Ajax
        Route::put('/{id}/update_ajax', [UserController::class, 'update_ajax']); // Menyimpan perubahan data user Ajax
        Route::post('/ajax', [UserController::class, 'store_ajax']);        // Menyimpan data user baru Ajax
        Route::get('/{id}/delete_ajax', [UserController::class, 'confirm_ajax']); // Untuk tampilkan form confirm delete user Ajax
        Route::delete('/{id}/delete_ajax', [UserController::class, 'delete_ajax']); // Untuk hapus data user Ajax
        Route::get('/{id}/show_ajax', [UserController::class, 'show_ajax']); // detail user ajax
        Route::delete('/{id}', [UserController::class, 'destroy']);         // menghapus data user
        Route::get('/import', [UserController::class, 'import']); // ajax form upload excel
        Route::post('/import_ajax', [UserController::class, 'import_ajax']); // ajax import excel
        Route::get('/export_excel', [UserController::class, 'export_excel']); // export excel
        Route::get('/export_pdf', [UserController::class, 'export_pdf']); // export pdf
    });

    // table level
    Route::group(['prefix' => 'level', 'middleware' =>  ['authorize:ADM']], function () {
        Route::get('/', [LevelController::class, 'index']);          // halaman awal level
        Route::post('/list', [LevelController::class, 'list']);      // data level dalam bentuk json untuk datatables
        Route::get('/create', [LevelController::class, 'create']);   // halaman form tambah level
        Route::post('/', [LevelController::class, 'store']);         // menyimpan data level baru
        Route::get('/{id}', [LevelController::class, 'show']);       // detail level
        Route::get('/{id}/edit', [LevelController::class, 'edit']);  // halaman form edit level
        Route::put('/{id}', [LevelController::class, 'update']);     // menyimpan perubahan data level
        Route::get('/create_ajax', [LevelController::class, 'create_ajax']); // halaman form tambah level Ajax
        Route::get('/{id}/edit_ajax', [LevelController::class, 'edit_ajax']); // halaman form edit level Ajax
        Route::put('/{id}/update_ajax', [LevelController::class, 'update_ajax']); // Menyimpan perubahan data level Ajax
        Route::post('/ajax', [LevelController::class, 'store_ajax']);        // Menyimpan data level baru Ajax
        Route::get('/{id}/delete_ajax', [LevelController::class, 'confirm_ajax']); // Untuk tampilkan form confirm delete level Ajax
        Route::delete('/{id}/delete_ajax', [LevelController::class, 'delete_ajax']); // Untuk hapus data level Ajax
        Route::get('/{id}/show_ajax', [LevelController::class, 'show_ajax']); // detail user ajax
        Route::delete('/{id}', [LevelController::class, 'destroy']); // menghapus data level
        Route::get('/import', [LevelController::class, 'import']); // ajax form upload excel
        Route::post('/import_ajax', [LevelController::class, 'import_ajax']); // ajax import excel
        Route::get('/export_excel', [LevelController::class, 'export_excel']); // export excel
        Route::get('/export_pdf', [LevelController::class, 'export_pdf']); // export pdf
    });


    // table kategori
    Route::group(['prefix' => 'kategori', 'middleware' =>  ['authorize:ADM']], function () {
        Route::get('/', [KategoriController::class, 'index']);          // halaman awal kategori
        Route::post('/list', [KategoriController::class, 'list']);      // data kategori dalam bentuk json untuk datatables
        Route::get('/create', [KategoriController::class, 'create']);   // halaman form tambah kategori
        Route::post('/', [KategoriController::class, 'store']);         // menyimpan data kategori baru
        Route::get('/{id}', [KategoriController::class, 'show']);       // detail kategori
        Route::get('/{id}/edit', [KategoriController::class, 'edit']);  // halaman form edit kategori
        Route::put('/{id}', [KategoriController::class, 'update']);     // menyimpan perubahan data kategori
        Route::get('/create_ajax', [KategoriController::class, 'create_ajax']); // halaman form tambah kategori Ajax
        Route::get('/{id}/edit_ajax', [KategoriController::class, 'edit_ajax']); // halaman form edit kategori Ajax
        Route::put('/{id}/update_ajax', [KategoriController::class, 'update_ajax']); // Menyimpan perubahan data kategori Ajax
        Route::post('/ajax', [KategoriController::class, 'store_ajax']);        // Menyimpan data kategori baru Ajax
        Route::get('/{id}/delete_ajax', [KategoriController::class, 'confirm_ajax']); // Untuk tampilkan form confirm delete kategori Ajax
        Route::delete('/{id}/delete_ajax', [KategoriController::class, 'delete_ajax']); // Untuk hapus data kategori Ajax
        Route::get('/{id}/show_ajax', [KategoriController::class, 'show_ajax']); // detail kategori ajax
        Route::delete('/{id}', [KategoriController::class, 'destroy']); // menghapus data kategori
        Route::get('/import', [KategoriController::class, 'import']); // ajax form upload excel
        Route::post('/import_ajax', [KategoriController::class, 'import_ajax']); // ajax import excel
        Route::get('/export_excel', [KategoriController::class, 'export_excel']); // export excel
        Route::get('/export_pdf', [KategoriController::class, 'export_pdf']); // export pdf
    });
    // table supplier
    Route::group(['prefix' => 'supplier', 'middleware' =>  ['authorize:ADM,MNG']], function () {
        Route::get('/', [SupplierController::class, 'index']);          // halaman awal supplier
        Route::post('/list', [SupplierController::class, 'list']);      // data supplier dalam bentuk json untuk datatables
        Route::get('/create', [SupplierController::class, 'create']);   // halaman form tambah supplier
        Route::post('/', [SupplierController::class, 'store']);         // menyimpan data supplier baru
        Route::get('/{id}', [SupplierController::class, 'show']);       // detail supplier
        Route::get('/{id}/edit', [SupplierController::class, 'edit']);  // halaman form edit supplier
        Route::put('/{id}', [SupplierController::class, 'update']);     // menyimpan perubahan data supplier
        Route::get('/create_ajax', [SupplierController::class, 'create_ajax']); // halaman form tambah supplier Ajax
        Route::get('/{id}/edit_ajax', [SupplierController::class, 'edit_ajax']); // halaman form edit supplier Ajax
        Route::put('/{id}/update_ajax', [SupplierController::class, 'update_ajax']); // Menyimpan perubahan data supplier Ajax
        Route::post('/ajax', [SupplierController::class, 'store_ajax']);        // Menyimpan data supplier baru Ajax
        Route::get('/{id}/delete_ajax', [SupplierController::class, 'confirm_ajax']); // Untuk tampilkan form confirm delete supplier Ajax
        Route::delete('/{id}/delete_ajax', [SupplierController::class, 'delete_ajax']); // Untuk hapus data supplier Ajax
        Route::get('/{id}/show_ajax', [SupplierController::class, 'show_ajax']); // detail supplier ajax
        Route::delete('/{id}', [SupplierController::class, 'destroy']); // menghapus data supplier
        Route::get('/import', [SupplierController::class, 'import']); // ajax form upload excel
        Route::post('/import_ajax', [SupplierController::class, 'import_ajax']); // ajax import excel
        Route::get('/export_excel', [SupplierController::class, 'export_excel']); // export excel
        Route::get('/export_pdf', [SupplierController::class, 'export_pdf']); // export pdf
    });
    // table barang
    Route::group(['prefix' => 'barang', 'middleware' =>  ['authorize:ADM,MNG']], function () {
        Route::get('/', [BarangController::class, 'index']);          // halaman awal barang
        Route::post('/list', [BarangController::class, 'list']);      // data barang dalam bentuk json untuk datatables
        Route::get('/create', [BarangController::class, 'create']);   // halaman form tambah barang
        Route::post('/', [BarangController::class, 'store']);         // menyimpan data barang baru
        Route::get('/{id}', [BarangController::class, 'show']);       // detail barang
        Route::get('/{id}/edit', [BarangController::class, 'edit']);  // halaman form edit barang
        Route::put('/{id}', [BarangController::class, 'update']);     // menyimpan perubahan data barang
        Route::get('/create_ajax', [BarangController::class, 'create_ajax']); // halaman form tambah barang Ajax
        Route::get('/{id}/edit_ajax', [BarangController::class, 'edit_ajax']); // halaman form edit barang Ajax
        Route::put('/{id}/update_ajax', [BarangController::class, 'update_ajax']); // Menyimpan perubahan data barang Ajax
        Route::post('/ajax', [BarangController::class, 'store_ajax']);        // Menyimpan data barang baru Ajax
        Route::get('/{id}/delete_ajax', [BarangController::class, 'confirm_ajax']); // Untuk tampilkan form confirm delete barang Ajax
        Route::delete('/{id}/delete_ajax', [BarangController::class, 'delete_ajax']); // Untuk hapus data barang Ajax
        Route::get('/{id}/show_ajax', [BarangController::class, 'show_ajax']); // detail barang ajax
        Route::get('/import', [BarangController::class, 'import']); // ajax form upload excel
        Route::post('/import_ajax', [BarangController::class, 'import_ajax']); // ajax import excel
        Route::get('/export_excel', [BarangController::class, 'export_excel']); // ajax export excel
        Route::get('/export_pdf', [BarangController::class, 'export_pdf']); // ajax export pdf
        Route::delete('/{id}', [BarangController::class, 'destroy']); // menghapus data barang
    });
    // table stok
    Route::group(['prefix' => 'stok', 'middleware' =>  ['authorize:ADM,MNG,STF']], function () {
        Route::get('/', [StokController::class, 'index']);          // menampilkan halaman awal Stok
        Route::post('/list', [StokController::class, 'list']);      // menampilkan data Stok dalam json untuk datables
        Route::get('/create_ajax', [StokController::class, 'create_ajax']); // Menampilkan halaman form tambah Stok Ajax
        Route::post('/ajax', [StokController::class, 'store_ajax']); // Menampilkan data Stok baru Ajax
        Route::get('/{id}/show_ajax', [StokController::class, 'show_ajax']);
        Route::get('/{id}/edit_ajax', [StokController::class, 'edit_ajax']); // Menampilkan halaman form edit Stok Ajax
        Route::put('/{id}/update_ajax', [StokController::class, 'update_ajax']); // Menyimpan perubahan data Stok Ajax
        Route::get('/{id}/delete_ajax', [StokController::class, 'confirm_ajax']); // Untuk tampilkan form confirm delete Stok Ajax
        Route::delete('/{id}/delete_ajax', [StokController::class, 'delete_ajax']); // Untuk hapus data Stok Ajax
        Route::delete('/{id}', [StokController::class, 'destroy']); // menghapus data Stok
        Route::get('/import', [StokController::class, 'import']); // ajax form upload excel
        Route::post('/import_ajax', [StokController::class, 'import_ajax']); // ajax import excel
        Route::get('/export_excel', [StokController::class, 'export_excel']); // export excel
        Route::get('/export_pdf', [StokController::class, 'export_pdf']); // export pdf
    });
    // table penjualan dan penjualan_detail
    Route::group(['prefix' => 'penjualan', 'middleware' =>  ['authorize:ADM,MNG,STF']], function () {
        Route::get('/', [PenjualanController::class, 'index']);          // menampilkan halaman awal Penjualan
        Route::post('/list', [PenjualanController::class, 'list']);      // menampilkan data Penjualan dalam json untuk datables
        Route::get('/create', [PenjualanController::class, 'create']);   // menampilkan halaman form tambah Penjualan
        Route::post('/', [PenjualanController::class, 'store']);          // menyimpan data Penjualan baru
        Route::get('/create_ajax', [PenjualanController::class, 'create_ajax']); // Menampilkan halaman form tambah Penjualan Ajax
        Route::get('/getHargaBarang/{id}', [PenjualanController::class, 'getHargaBarang']);
        Route::post('/ajax', [PenjualanController::class, 'store_ajax']); // Menampilkan data Penjualan baru Ajax
        Route::get('/{id}', [PenjualanController::class, 'show']);       // menampilkan detail Penjualan
        Route::get('/{id}/show_ajax', [PenjualanController::class, 'show_ajax']);
        Route::get('/{id}/edit', [PenjualanController::class, 'edit']);  // menampilkan halaman form edit Penjualan
        Route::put('/{id}', [PenjualanController::class, 'update']);     // menyimpan perubahan data Penjualan
        Route::get('/{id}/edit_ajax', [PenjualanController::class, 'edit_ajax']); // Menampilkan halaman form edit Penjualan Ajax
        Route::put('/{id}/update_ajax', [PenjualanController::class, 'update_ajax']); // Menyimpan perubahan data Penjualan Ajax
        Route::get('/{id}/delete_ajax', [PenjualanController::class, 'confirm_ajax']); // Untuk tampilkan form confirm delete Penjualan Ajax
        Route::delete('/{id}/delete_ajax', [PenjualanController::class, 'delete_ajax']); // Untuk hapus data Penjualan Ajax
        Route::delete('/{id}', [PenjualanController::class, 'destroy']); // menghapus data Penjualan
        Route::get('/import', [PenjualanController::class, 'import']); // ajax form upload excel
        Route::post('/import_ajax', [PenjualanController::class, 'import_ajax']); // ajax import excel
        Route::get('/export_excel', [PenjualanController::class, 'export_excel']); // export excel
        Route::get('/export_pdf', [PenjualanController::class, 'export_pdf']); // export pdf
    });
});
