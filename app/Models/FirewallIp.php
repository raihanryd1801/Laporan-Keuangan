<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FirewallIp extends Model
{
    use HasFactory;

    // Tambahkan baris ini agar kolom ip_address dan keterangan bisa disimpan secara massal
    protected $fillable = [
        'ip_address',
        'keterangan',
    ];
}