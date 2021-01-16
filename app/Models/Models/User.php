<?php

namespace App\Models\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
//use Illuminate\Database\Eloquent\Model;
//use Illuminate\Foundation\Auth\User ;//as Authenticatable;
use Tymon\JWTAuth\Contracts\JWTSubject;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Contracts\Auth\Authenticatable;

class User extends Model implements Authenticatable 
{
    use \Illuminate\Auth\Authenticatable;
    use HasFactory;
    
    use Notifiable;
    protected $primaryKey = 'pass';
    protected $fillable = [
        'gmail',
        'pass'       
    ];

    protected $table = "users";
    public function San()
    {
        return $this->hasMany('App\Models\Models\DatSan', 'iduser', 'id');
    }
}
