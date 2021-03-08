<?php

namespace Database\Seeders;

use App\Models\Models\DoanhThu;

use Illuminate\Database\Seeder;

class DoanhThuSeeder extends Seeder
{
    public function run()
    {
        $dt=[1560000, 830000, 1370000, 1330000, 1350000, 1130000, 1630000, 1530000, 1250000, 900000];
        for ($nam=2020; $nam < 2022; $nam++) { 
            for ($thang=1; $thang < 13; $thang++) { 
                for ($ngay=1; $ngay < 29; $ngay++) { 
                    for ($idquan=1; $idquan < 10; $idquan++) {
                        $data = [
                            "idquan" => $idquan,
                            "doanhthu" => $dt[mt_rand(0, 8)],
                            "time" =>$nam."-".$thang."-".$ngay." 00:00:00"
                        ];
                        DoanhThu::insert($data);
                
                    }
                }
            }
        }
                
    }
}
