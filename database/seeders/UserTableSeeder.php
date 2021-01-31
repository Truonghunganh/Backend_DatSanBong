<?php

namespace Database\Seeders;
use App\Models\Models\User;
use Tymon\JWTAuth\Facades\JWTAuth;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Tymon\JWTAuth\Facades\JWTFactory;
use Illuminate\Database\Seeder;

class UserTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        
        $data=[
            "name"=>"Dương huỳnh quang",
            "role"=>"user",
            "phone"=> "0796234625",
            "gmail"=>"quang@gmail.com",
            "address"=>"thành Phố thừa thiên huế",
            "password" => bcrypt("0796234625"),
            "Create_time" => Carbon::now()
        ];
        User::insert($data);
        $user = User::where("phone", "=", "0796234625")->get();
        if (count($user) > 0) {
            $token = JWTAuth::fromUser($user[0]);
            
            DB::update('update users set token = ? where phone = ?', [$token, '0796234625']);
            
        }
                
        $data = [
            "name" => "Trương Hùng Anh",
            "role" => "user",
            "phone" => "0812250590",
            "gmail" => "hunganh250590@gmail.com",
            "address" => "nhị dinh 3-điện Phước -điện bàn-quảng nam",
            "password" =>bcrypt ("0812250590"),
            "Create_time"=> Carbon::now()
        ];
        User::insert($data);
        $user = User::where("phone", "=", "0812250590")->get();
        if (count($user) > 0) {
            $token = JWTAuth::fromUser($user[0]);
            DB::update('update users set token = ? where phone = ?', [$token, '0812250590']);
            
        }
        $data = [
            "name" => "Võ đức hùng sơn",
            "role" => "innkeeper",
            "phone" => "0123456789",
            "gmail" => "son@gmail.com",
            "address" => "thành Phố thừa thiên huế",
            "password" => bcrypt("0123456789"),
            "Create_time" => Carbon::now()
        ];
        User::insert($data);
        $user = User::where("phone", "=", "0123456789")->get();
        if (count($user) > 0) {
            $token = JWTAuth::fromUser($user[0]);

            DB::update('update users set token = ? where phone = ?', [$token, '0123456789']);
        }
   
        $data = [
            "name" => "nguyễn thái quyên",
            "role" => "innkeeper",
            "phone" => "0987654321",
            "gmail" => "quyen@gmail.com",
            "address" => "Điện hòa-điện bàn-quảng nam",
            "password" => bcrypt("0987654321"),
            "Create_time" => Carbon::now()
        ];
        User::insert($data);
        $user = User::where("phone", "=", "0987654321")->get();
        if (count($user) > 0) {
            $token = JWTAuth::fromUser($user[0]);

            DB::update('update users set token = ? where phone = ?', [$token, '0987654321']);
        }


        $data = [
            "name" => "đồ thị minh thúy",
            "role" => "admin",
            "phone" => "6574839201",
            "gmail" => "thuy@gmail.com",
            "address" => "Điện hòa-điện bàn-quảng nam",
            "password" => bcrypt("6574839201"),
            "Create_time" => Carbon::now()
        ];
        User::insert($data);
        $user = User::where("phone", "=", "6574839201")->get();
        if (count($user) > 0) {
            $token = JWTAuth::fromUser($user[0]);

            DB::update('update users set token = ? where phone = ?', [$token, '6574839201']);
        }

    }
}
