<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class LevelModel extends Model
{
    use HasFactory;

    protected $table = 'm_level'; // Mendefinisikan nama tabel yang benar
    protected $primaryKey = 'level_id'; // Mendefinisikan primary key dari tabel yang digunakan
    protected $fillable = ['level_kode','level_nama','created_at', 'updated_at'];
}
