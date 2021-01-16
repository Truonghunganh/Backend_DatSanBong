<?php

namespace App\Services;

use App\Models\Models\User;
use Illuminate\Support\Facades\DB;

class UserService
{
    public function getListDatSanByIduser($request)
    {
        if ($request->get("iduser")) {
            return DB::table('datsans')->where('iduser', $request->get('iduser'));
        }
        return [];
    }
    public function getUserById($id)
    {
        return DB::table('users')->where('id', $id)->get();
    }
    
}