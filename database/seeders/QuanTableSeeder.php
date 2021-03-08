<?php


namespace Database\Seeders;

use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

use Illuminate\Database\Seeder;

class QuanTableSeeder extends Seeder
{
    public function run()
    {
        //1
        $data = [
            "name" => "MU",
            "image"=>"image/Quan/mu.jpg",
            "address"=>"60-Ngô Sĩ liên-Đà nẵng",
            "phone" => "0812250590",
            "linkaddress" => "https://www.google.com/maps/place/60+Ngô+Sĩ+Liên,+Hoà+Khánh+Bắc,+Liên+Chiểu,+Đà+Nẵng+550000,+Việt+Nam/@16.0739926,108.1511769,17z/data=!3m1!4b1!4m5!3m4!1s0x314218d76b1a334f:0xeca62349bfbee122!8m2!3d16.0739875!4d108.1533656?hl=vi-VN",
            "vido" => "16.0739875",
            "kinhdo" => "108.1533656",
            "trangthai" =>true,
            "Create_time" => Carbon::now()
        ];
        DB::table('quans')->insert($data);
        //2
        $data = [
            "name" => "Sân bóng đá Chuyên Việt",
            "image" => "image/Quan/hunganh.jpg",
            "address" => "98 Tiểu La, Hòa Thuận Đông, Hải Châu, Đà Nẵng",
            "phone" => "0812250590",
            "linkaddress" => "https://www.google.com/maps/dir/16.0732797,108.1526873/Sân+bóng+đá+Chuyên+Việt,+98+Tiểu+La,+Hòa+Thuận+Đông,+Hải+Châu,+Đà+Nẵng+550000,+Việt+Nam/@16.0507264,108.148344,13z/data=!3m1!4b1!4m9!4m8!1m1!4e1!1m5!1m1!1s0x314219c0801817c3:0x1702bb03f6985b2f!2m2!1d108.214369!2d16.0451026?hl=vi-VN",
            "vido" => "16.0451026",
            "kinhdo" => "108.214369",
            "trangthai" => true,
            "Create_time" => Carbon::now()
        ];
        
        DB::table('quans')->insert($data);
        //3
        $data = [
            "name" => "Sân bóng đá Chuyên Việt",
            "image" => "image/Quan/a.jpg",
            "address" => "H28 Âu Cơ, Hoà Khánh Bắc, Liên Chiểu, Đà Nẵng ",
            "phone" => "0337265910",
            "linkaddress" => "https://www.google.com/maps/dir/16.0732797,108.1526873/Sân+Bóng+Đá+Chuyên+Việt,+Âu+Cơ,+Hòa+Khánh+Bắc,+Liên+Chiểu,+Đà+Nẵng/@16.0734531,108.1431804,16z/data=!3m1!4b1!4m9!4m8!1m1!4e1!1m5!1m1!1s0x3142192aa90315ad:0x8f7bdad44ca1fa4a!2m2!1d108.1434063!2d16.0706259?hl=vi-VN",
            "vido" => "16.0706259",
            "kinhdo" => "108.1434063",
            "trangthai" => true,
            "Create_time" => Carbon::now()
        ];
        DB::table('quans')->insert($data);
        //4
        $data = [
            "name" => "Sân Bóng Đá Đạt Phúc",
            "image" => "image/Quan/b.jpg",
            "address" => "19 Phạm Như Xương, Hoà Khánh Nam, Liên Chiểu, Đà Nẵng",
            "phone" => "0337265910",
            "linkaddress" => "https://www.google.com/maps/place/Sân+Bóng+Đá+Đạt+Phúc/@16.0628798,108.1553149,18z/data=!3m1!4b1!4m13!1m7!3m6!1s0x31421925c109130d:0x110d3d79bf5249da!2zMTkgUGjhuqFtIE5oxrAgWMawxqFuZywgSG_DoCBLaMOhbmggTmFtLCBMacOqbiBDaGnhu4N1LCDEkMOgIE7hurVuZyA1NTAwMDAsIFZp4buHdCBOYW0!3b1!8m2!3d16.0628772!4d108.1564092!3m4!1s0x31421925c109130d:0x4bacd1f6e3df235d!8m2!3d16.0628772!4d108.1564092?hl=vi-VN",
            "vido" => "16.0628772",
            "kinhdo" => "108.1564092",
            "trangthai" => true,
            "Create_time" => Carbon::now()
        ];
        DB::table('quans')->insert($data);
        //5
        $data = [
            "name" => "Sân Bóng Manchester United",
            "image" => "image/Quan/c.jpg",
            "address" => "59 Ngô Thì Nhậm, Hoà Khánh Nam, Liên Chiểu, Đà Nẵng 550000, Việt Nam",
            "phone" => "0354658717",
            "linkaddress" => "https://www.google.com/maps/place/Sân+Bóng+Manchester+United/@16.070761,108.1542209,21z/data=!4m13!1m7!3m6!1s0x314218d7f0da97ff:0x2d96e2ad56a12368!2zNjEgTmfDtCBUaMOsIE5o4bqtbSwgSG_DoCBLaMOhbmggTmFtLCBMacOqbiBDaGnhu4N1LCDEkMOgIE7hurVuZyA1NTAwMDAsIFZp4buHdCBOYW0!3b1!8m2!3d16.0707349!4d108.1543061!3m4!1s0x314218d82271624f:0xe435529625132578!8m2!3d16.0708556!4d108.1544331?hl=vi-VN",
            "vido" => "16.0708556",
            "kinhdo" => "108.1544331",
            "trangthai" => true,
            "Create_time" => Carbon::now()
        ];
        DB::table('quans')->insert($data);
        //6
        $data = [
            "name" => "Sân Bóng Đá Nam Cao",
            "image" => "image/Quan/d.jpg",
            "address" => "169 Đ. Nam Cao -Hoà Khánh Nam -Liên Chiểu Đà Nẵng",
            "phone" => "0356899335",
            "linkaddress" => "https://www.google.com/maps/place/Sân+Bóng+Đá+Nam+Cao/@16.063911,108.1455569,17z/data=!4m13!1m7!3m6!1s0x3142192c0e642f17:0x12442573c37593ad!2zMTY5IMSQLiBOYW0gQ2FvLCBIb8OgIEtow6FuaCBOYW0sIExpw6puIENoaeG7g3UsIMSQw6AgTuG6tW5nIDU1MDAwMCwgVmnhu4d0IE5hbQ!3b1!8m2!3d16.0639059!4d108.1477456!3m4!1s0x3142199289c312f7:0x23704af6ca5703fd!8m2!3d16.0633224!4d108.1491009?hl=vi-VN",
            "vido" => "16.0633224",
            "kinhdo" => "108.1491009",
            "trangthai" => true,
            "Create_time" => Carbon::now()
        ];
        DB::table('quans')->insert($data);
        //7
        $data = [
            "name" => "Sân Bóng Đá Nguyễn Chánh",
            "image" => "image/Quan/e.jpg",
            "address" => "86 Nguyễn Chánh, Hoà Khánh Bắc, Liên Chiểu, Đà Nẵng ",
            "phone" => "0356899336",
            "linkaddress" => "https://www.google.com/maps/place/86+Nguyễn+Chánh,+Hoà+Khánh+Bắc,+Liên+Chiểu,+Đà+Nẵng+550000,+Việt+Nam/@16.0842914,108.1487053,17z/data=!4m13!1m7!3m6!1s0x314218ce5871addf:0x41806cf5617e3407!2zODYgTmd1eeG7hW4gQ2jDoW5oLCBIb8OgIEtow6FuaCBC4bqvYywgTGnDqm4gQ2hp4buDdSwgxJDDoCBO4bq1bmcgNTUwMDAwLCBWaeG7h3QgTmFt!3b1!8m2!3d16.0842863!4d108.150894!3m4!1s0x314218ce5871addf:0x41806cf5617e3407!8m2!3d16.0842863!4d108.150894?hl=vi-VN",
            "vido" => "16.0842863",
            "kinhdo" => "108.150894",
            "trangthai" => true,
            "Create_time" => Carbon::now()
        ];
        DB::table('quans')->insert($data);
        //8
        $data = [
            "name" => "Sân bóng Thanh Thanh",
            "image" => "image/Quan/f.jpg",
            "address" => "79 Ngô Văn Sơ, Hoà Khánh Bắc, Liên Chiểu, Đà Nẵng ",
            "phone" => "0787179937",
            "linkaddress" => "https://www.google.com/maps/place/Sân+bóng+Thanh+Thanh/@16.0676506,108.1471403,18z/data=!4m5!3m4!1s0x314219297ceb4635:0xe4aab088d70be14!8m2!3d16.0669193!4d108.1490714?hl=vi-VN",
            "vido" => "16.0669193",
            "kinhdo" => "108.1490714",
            "trangthai" => true,
            "Create_time" => Carbon::now()
        ];
        DB::table('quans')->insert($data);
        //9
        $data = [
            "name" => "Sân Bóng Đá Ngọc Thạch",
            "image" => "image/Quan/g.jpg",
            "address" => "207 Phạm Như Xương, Hoà Khánh Nam, Liên Chiểu, Đà Nẵng ",
            "phone" => "0935291246",
            "linkaddress" => "https://www.google.com/maps/place/207+Phạm+Như+Xương,+Hoà+Khánh+Nam,+Liên+Chiểu,+Đà+Nẵng+550000,+Việt+Nam/@16.0656956,108.1477859,19z/data=!4m13!1m7!3m6!1s0x3142192be4ec3be3:0x11eaae64126c9f6e!2zMjA3IFBo4bqhbSBOaMawIFjGsMahbmcsIEhvw6AgS2jDoW5oIE5hbSwgTGnDqm4gQ2hp4buDdSwgxJDDoCBO4bq1bmcgNTUwMDAwLCBWaeG7h3QgTmFt!3b1!8m2!3d16.0655835!4d108.1483143!3m4!1s0x3142192be4ec3be3:0x11eaae64126c9f6e!8m2!3d16.0655835!4d108.1483143?hl=vi-VN",
            "vido" => "16.0655835",
            "kinhdo" => "108.1483143",
            "trangthai" => true,
            "Create_time" => Carbon::now()
        ];
        DB::table('quans')->insert($data);
        //10
        $data = [
            "name" => "Sân Bóng đá Trưng Vương",
            "image" => "image/Quan/h.jpg",
            "address" => "403 Trưng Nữ Vương, Hòa Thuận Nam, Hải Châu, Đà Nẵng",
            "phone" => "0812250590",
            "linkaddress" => "https://www.google.com/maps/place/Sân+Bóng+đá+Trưng+Vương/@16.0462637,108.2082137,17z/data=!4m13!1m7!3m6!1s0x314219bee5e41971:0x9a171ad90134e854!2zNTYwIFRyxrBuZyBO4buvIFbGsMahbmcsIEjDsmEgVGh14bqtbiBOYW0sIEjhuqNpIENow6J1LCDEkMOgIE7hurVuZyA1NTAwMDAsIFZp4buHdCBOYW0!3b1!8m2!3d16.0462586!4d108.2104024!3m4!1s0x314219bee68e5add:0xe5e9b113bc37fd33!8m2!3d16.0471609!4d108.2100709?hl=vi-VN",
            "vido" => "16.0471609",
            "kinhdo" => "108.2100709",
            "trangthai" => false,
            "Create_time" => Carbon::now()
        ];
        DB::table('quans')->insert($data);
        
    }
}
