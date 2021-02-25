<?php

namespace App\Models\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DanhThu extends Model
{
    use HasFactory;
    protected $fillable = [
        'idquan',
        'danhthutheothang',
        'time',
    ];
    public $timestamps = false;
    protected $table = "danhthus";
    
}
