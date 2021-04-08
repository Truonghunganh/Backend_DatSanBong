<?php

namespace App\Services;

use Tymon\JWTAuth\Facades\JWTAuth;
//use JWTAuth;
use Illuminate\Support\Facades\Auth;
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
    public function getUserById($id){
        $user = DB::table('users')->where('id', $id)->first();
        return new User1($user->id, $user->name, $user->phone, $user->gmail, $user->address);
    }

    public function getUserByUser($user)
    {
        return new User1($user->id, $user->name, $user->phone, $user->gmail, $user->address);
    }
    
    public function editUserByToken($request,$id)
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
    public function register($request)
    {
        DB::beginTransaction();
        try {
            $userCheckPhone = User::where('phone', '=', $request->get('phone'))->first();
            if ($userCheckPhone) {
                return true;
            }
            date_default_timezone_set("Asia/Ho_Chi_Minh");
            $time = date('Y-m-d H:i:s');

            DB::insert(
                'insert into users (role,name,phone,gmail,address,password,Create_time) values (?,?, ?,?, ?,?,?)',
                [
                    $request->get('role'),
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
            
            DB::commit();
            return false;
        } catch (\Exception $e) {
            DB::rollBack();
            //return false;
            throw new \Exception($e->getMessage());
        }
    }
    
    // public function registerUser($request){
    //     $userCheckPhone= User::where('phone','=', $request->get('phone'))->first();
    //     if($userCheckPhone){
    //         return true;
    //     }
    //     date_default_timezone_set("Asia/Ho_Chi_Minh");
    //     $time = date('Y-m-d H:i:s');
         
    //     DB::insert(
    //         'insert into users (role,name,phone,gmail,address,password,Create_time) values (?,?, ?,?, ?,?,?)', 
    //     [
    //         "user",
    //         $request->get('name'),
    //         $request->get('phone'),
    //         $request->get('gmail'),
    //         $request->get('address'),
    //         bcrypt($request->get('password')),
    //         $time

    //     ]);
    //     $user = User::where("phone", "=", $request->get('phone'))->first();
    //     if ($user) {
    //         $token = JWTAuth::fromUser($user);
    //         DB::update(
    //             'update users set token = ? where phone = ?',
    //             [$token, $request->get('phone')]
    //         );
    //     }
    //     return false;
      
    // }
    public  function getUser($request)
    {
        return User::where("phone", "=", $request->get('phone'))->first();
    }

    
       
}
class User1
{
    public $id;
    public $name;
    public $phone;
    public $gmail;
    public $address;
    public function __construct($id, $name, $phone, $gmail, $address)
    {
        $this->id = $id;
        $this->name = $name;
        $this->phone = $phone;
        $this->gmail = $gmail;
        $this->address = $address;
    }
}