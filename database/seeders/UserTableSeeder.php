<?php

namespace Database\Seeders;

use Illuminate\Support\Facades\DB;

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
            "name"=>"son",
            "phone"=>"079623425",
            "gmail"=>"son@gmail.com",
            "address"=>"nhị dinh 3-điện Phước -điện bàn-quảng nam",
            "password" => bcrypt(123456),
        ];
        DB::table('users')->insert($data);       
    }
}
