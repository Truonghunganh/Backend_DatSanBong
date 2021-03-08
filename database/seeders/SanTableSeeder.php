<?php

namespace Database\Seeders;

use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

use Illuminate\Database\Seeder;

class SanTableSeeder extends Seeder
{
    public function run()
    {
        for ($i=1; $i <10 ; $i++) {
            $data = [
                "name" => "Sân A",
                "idquan" => $i,
                "numberpeople" => 5,
                "priceperhour" => 150000,
                "trangthai" => true,
                "Create_time" => Carbon::now()
            ];
            DB::table('sans')->insert($data);
            $data = [
                "name" => "Sân B",
                "idquan" => $i,
                "numberpeople" => 5,
                "priceperhour" => 150000,
                "trangthai" => true,
                "Create_time" => Carbon::now()
            ];
            DB::table('sans')->insert($data);
            $data = [
                "name" => "Sân C",
                "idquan" => $i,
                "numberpeople" => 7,
                "priceperhour" => 200000,
                "trangthai" => true,
                "Create_time" => Carbon::now()
            ];
            DB::table('sans')->insert($data);
        
        }
                                 
    }
}
