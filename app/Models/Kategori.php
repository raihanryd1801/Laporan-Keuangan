<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Kategori extends Model
{
    use HasFactory;

    protected $table = 'kategoris'; // Sesuaikan jika nama tabel database-nya kategoris
    protected $fillable = ['nama_kategori'];
}