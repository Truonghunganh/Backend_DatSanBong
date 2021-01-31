<?php

namespace App\Models\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Device_token extends Model
{
    use HasFactory;
    protected $fillable = [
        "role_id",
        "phone",
        "token",
    ];
    protected $hidden = [
        "role_id",
        "phone",
        "token",
    ];
    protected $table = "device_tokens";
    
}
