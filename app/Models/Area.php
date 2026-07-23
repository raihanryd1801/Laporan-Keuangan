<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Area extends Model
{
    use HasFactory;

    // Tambahkan baris ini untuk mengizinkan kolom nama_area diisi
    protected $fillable = ['nama_area'];
}