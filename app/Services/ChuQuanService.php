<?php

namespace App\Services;

use Tymon\JWTAuth\Facades\JWTAuth;
use Illuminate\Support\Facades\Auth;
use App\Models\Models\User;

use Illuminate\Support\Facades\DB;

class ChuQuanService
{
    public function registerInnkeeper($request)
    {
        $userCheckPhone = User::where('phone', '=', $request->get('phone'))->get();
        if (count($userCheckPhone) > 0) {
            return true;
        }
        date_default_timezone_set("Asia/Ho_Chi_Minh");
        $time = date('Y-m-d H:i:s');
           DB::insert(
            'insert into users (role,name,phone,gmail,address,password,Create_time) values (?,?, ?,?, ?,?,?)',
            [
                "innkeeper",
                $request->get('name'),
                $request->get('phone'),
                $request->get('gmail'),
                $request->get('address'),
                bcrypt($request->get('password')),
                $time

            ]
        );
        $user = User::where("phone", "=", $request->get('phone'))->first();
        if ($user) {
            $token = JWTAuth::fromUser($user);
            DB::update(
                'update users set token = ? where phone = ?',
                [$token, $request->get('phone')]
            );
        }
        return false;
    }

    public function editInnkeeperByToken($request, $id)
    {
        date_default_timezone_set("Asia/Ho_Chi_Minh");
        $time = date('Y-m-d H:i:s');
         
        DB::update(
            'update users set name=?,gmail=?,address=?,password=?,Create_time=? where id = ?',
            [
                $request->get('name'),
                $request->get('gmail'),
                $request->get('address'),
                bcrypt($request->get('password')),
                $time,
                $id

            ]
        );
        $user = User::where("id", "=", $id)->first();
        if ($user)  {
            $token = JWTAuth::fromUser($user);
            DB::update(
                'update users set token = ? where phone = ?',
                [$token, $request->get('phone')]
            );
            return $token;
        } else {
            return "id k có nên k có token";
        }
    }
    
    

}
