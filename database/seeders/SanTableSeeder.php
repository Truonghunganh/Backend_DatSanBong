<?php

namespace Database\Seeders;

use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

use Illuminate\Database\Seeder;

class SanTableSeeder extends Seeder
{
    public function run()
    {
        $data = [
            "name" => "Sân A",
            "idquan" => 1,
            "numberpeople" => 5,
            "priceperhour" => 150000,
            "trangthai" => true,
            "Create_time" => Carbon::now()
        ];
        DB::table('sans')->insert($data);
        $data = [
            "name" => "Sân B",
            "idquan" => 1,
            "numberpeople" => 5,
            "priceperhour" => 150000,
            "trangthai" => true,
            "Create_time" => Carbon::now()
        ];
        DB::table('sans')->insert($data);
        $data = [
            "name" => "Sân C",
            "idquan" => 1,
            "numberpeople" => 7,
            "priceperhour" => 200000,
            "trangthai" => true,
            "Create_time" => Carbon::now()
        ];
        DB::table('sans')->insert($data);
        
        $data = [
            "name" => "Sân A",
            "idquan" => 2,
            "numberpeople" => 5,
            "priceperhour" => 150000,
            "trangthai" => true,
            "Create_time" => Carbon::now()
        ];
        DB::table('sans')->insert($data);
        $data = [
            "name" => "Sân B",
            "idquan" => 2,
            "numberpeople" => 5,
            "priceperhour" => 150000,
            "trangthai" => true,
            "Create_time" => Carbon::now()
        ];
        DB::table('sans')->insert($data);
        $data = [
            "name" => "Sân C",
            "idquan" => 2,
            "numberpeople" => 7,
            "priceperhour" => 200000,
            "trangthai" => true,
            "Create_time" => Carbon::now()
        ];
        DB::table('sans')->insert($data);
                         
    }
}
