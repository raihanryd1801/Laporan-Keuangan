<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Fail2banConfig extends Model
{
    protected $table = 'fail2ban_configs';
    protected $fillable = ['jail_name', 'maxretry', 'bantime', 'ignoreip'];
}