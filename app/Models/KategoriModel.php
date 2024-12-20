<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class KategoriModel extends Model
{
    use HasFactory;
    protected $table = 'm_kategori'; // Mendefinisikan nama tabel yang benar
    protected $primaryKey = 'kategori_id'; // Mendefinisikan primary key dari tabel yang digunakan
    protected $fillable = ['kategori_kode','kategori_nama','created_at', 'updated_at'];
}
