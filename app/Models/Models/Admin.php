<?php

namespace App\Models\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Admin extends Model
{
    use HasFactory;

    protected $fillable = [
        'id',
        'name',
        'phone',
        'gmail',
        'address',
        'pass'

    ];

    protected $table = "admins";

}
