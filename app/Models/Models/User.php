<?php

namespace App\Models\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class User extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'phone',
        'gmail',
        'address',
        'pass'
           
    ];

    protected $table = "users";
    public function San()
    {
        return $this->hasMany('App\Models\Models\DatSan', 'iduser', 'id');
    }
}
