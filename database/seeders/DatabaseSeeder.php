<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        // \App\Models\User::factory(10)->create();
        $this->call([
            QuanTableSeeder::class,
            SanTableSeeder::class,
            UserTableSeeder::class,
        ]);

    }
}

// INSERT INTO `datsans`( `idsan`, `iduser`, `start_time`, `price`, `xacnhan`, `Create_time`) VALUES (4,2,"2021-01-13 11:00:00",150000,1,"2021-01-13 08:00:00");
// INSERT INTO `datsans`( `idsan`, `iduser`, `start_time`, `price`, `xacnhan`, `Create_time`) VALUES (4,2,"2021-01-13 12:00:00",150000,1,"2021-01-13 08:00:00");
// INSERT INTO `datsans`( `idsan`, `iduser`, `start_time`, `price`, `xacnhan`, `Create_time`) VALUES (4,2,"2021-01-13 13:00:00",150000,1,"2021-01-13 08:00:00");
// INSERT INTO `datsans`( `idsan`, `iduser`, `start_time`, `price`, `xacnhan`, `Create_time`) VALUES (4,2,"2021-01-13 14:00:00",150000,1,"2021-01-13 08:00:00");
// INSERT INTO `datsans`( `idsan`, `iduser`, `start_time`, `price`, `xacnhan`, `Create_time`) VALUES (4,2,"2021-02-14 11:00:00",150000,1,"2021-01-13 08:00:00");
// INSERT INTO `datsans`( `idsan`, `iduser`, `start_time`, `price`, `xacnhan`, `Create_time`) VALUES (4,2,"2021-02-15 11:00:00",150000,1,"2021-01-13 08:00:00");
// INSERT INTO `datsans`( `idsan`, `iduser`, `start_time`, `price`, `xacnhan`, `Create_time`) VALUES (4,2,"2021-02-16 11:00:00",150000,1,"2021-01-13 08:00:00");
// INSERT INTO `datsans`( `idsan`, `iduser`, `start_time`, `price`, `xacnhan`, `Create_time`) VALUES (4,2,"2021-02-17 11:00:00",150000,1,"2021-01-13 08:00:00");
// INSERT INTO `datsans`( `idsan`, `iduser`, `start_time`, `price`, `xacnhan`, `Create_time`) VALUES (4,2,"2021-02-17 16:00:00",150000,1,"2021-01-13 08:00:00");
// INSERT INTO `datsans`( `idsan`, `iduser`, `start_time`, `price`, `xacnhan`, `Create_time`) VALUES (4,2,"2021-02-13 19:00:00",150000,1,"2021-01-13 08:00:00");
