<?php

namespace App\Services;

use Tymon\JWTAuth\Facades\JWTAuth;
//use JWTAuth;
use Illuminate\Support\Facades\Auth;
use App\Models\Models\User;

use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class UserService
{
    public function getListDatSanByIduser($request)
    {
        if ($request->get("iduser")) {
            return DB::table('datsans')->where('iduser', $request->get('iduser'));
        }
        return [];
    }
    public function editUserByToken($request,$id)
    {
        DB::update(
        'update users set name=?,gmail=?,address=?,password=?,Create_time=? where id = ?',
            [
                $request->get('name'),
                $request->get('gmail'),
                $request->get('address'),
                bcrypt($request->get('password')),
                Carbon::now(), 
                $id

            ]
        );
        $user = User::where("id", "=", $id)->get();
        if (count($user) > 0) {
            $token = JWTAuth::fromUser($user[0]);
            DB::update(
                'update users set token = ? where phone = ?',
                [$token, $request->get('phone')]
            );
            return $token;
        }else {
            return "id k có nên k có token";     
        }
      
    }
    
    public function getTokenUser($request,$role){
        return DB::table('users')->where('role', '=', $role)->where('phone', $request->get('phone'))->get()[0]->token;
    }
    public function registerUser($request){
        $userCheckPhone= User::where('phone','=', $request->get('phone'))->get();
        if(count($userCheckPhone)>0){
            return true;
        }
        DB::insert(
            'insert into users (role,name,phone,gmail,address,password,Create_time) values (?,?, ?,?, ?,?,?)', 
        [
            "user",
            $request->get('name'),
            $request->get('phone'),
            $request->get('gmail'),
            $request->get('address'),
            bcrypt($request->get('password')),
            Carbon::now()

        ]);
        $user = User::where("phone", "=", $request->get('phone'))->get();
        if (count($user) > 0) {
            $token = JWTAuth::fromUser($user[0]);
            DB::update(
                'update users set token = ? where phone = ?',
                [$token, $request->get('phone')]
            );
        }
        return false;
      
    }
    public  function getUser($request)
    {
        return User::where("phone", "=", $request->get('phone'))->get()[0];
    }

    
       
}
