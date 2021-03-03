<?php


namespace Database\Seeders;

use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

use Illuminate\Database\Seeder;

class QuanTableSeeder extends Seeder
{
    public function run()
    {
        $data = [
            "name" => "MU",
            "image"=>"image/Quan/mu.jpg",
            "address"=>"60-Ngô Sĩ liên-Đà nẵng",
            "phone" => "0987654321",
            "linkaddress" => "https://www.google.com/maps/place/60+Ngô+Sĩ+Liên,+Hoà+Khánh+Bắc,+Liên+Chiểu,+Đà+Nẵng+550000,+Việt+Nam/@16.0739926,108.1511769,17z/data=!3m1!4b1!4m5!3m4!1s0x314218d76b1a334f:0xeca62349bfbee122!8m2!3d16.0739875!4d108.1533656?hl=vi-VN",
            "trangthai" =>true,
            "Create_time" => Carbon::now()
        ];
        DB::table('quans')->insert($data);
        $data = [
            "name" => "Hùng Anh",
            "image" => "image/Quan/hunganh.jpg",
            "address" => "60-Ngô Sĩ liên-Đà nẵng",
            "phone" => "0123456789",
            "linkaddress" => "https://www.google.com/maps/place/60+Ngô+Sĩ+Liên,+Hoà+Khánh+Bắc,+Liên+Chiểu,+Đà+Nẵng+550000,+Việt+Nam/@16.0739926,108.1511769,17z/data=!3m1!4b1!4m5!3m4!1s0x314218d76b1a334f:0xeca62349bfbee122!8m2!3d16.0739875!4d108.1533656?hl=vi-VN",
            "trangthai" => true,
            "Create_time" => Carbon::now()
        ];
        DB::table('quans')->insert($data);
    
    }
}
