<?php

namespace App\Models\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DoanhThu extends Model
{
    use HasFactory;
    protected $fillable = [
        'idquan',
        'doanhthutheothang',
        'time',
    ];
    public $timestamps = false;
    protected $table = "doanhthus";
    
}
