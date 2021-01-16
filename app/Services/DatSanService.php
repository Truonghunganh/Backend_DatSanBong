<?php

namespace App\Services;

use Carbon\Carbon;

use Illuminate\Support\Facades\DB;
//use App\Models\Models\DatSan;

class DatSanService
{

    public function getListDatSanByIduser($iduser)
    {
        $listdatsanByiduser= DB::table('datsans')->where('iduser', $iduser)->where('start_time','>=', Carbon::now())->get();
        $mangdatsantruocngayhientai=[];
        for ($i=0; $i < count($listdatsanByiduser); $i++) { 
            $san= DB::table('sans')->where('id','=', $listdatsanByiduser[$i]->idsan)->get();
            $quan= DB::table('quans')->where('id','=',$san[0]->idquan)->get();
            $datsan=new datsan($listdatsanByiduser[$i]->id,$quan[0]->name,$quan[0]->address,$quan[0]->phone,$san[0]->name,$listdatsanByiduser[$i]->start_time,$listdatsanByiduser[$i]->price);
            array_push($mangdatsantruocngayhientai,$datsan);
        }
        $keys = array_column($mangdatsantruocngayhientai, 'time');
        // SORT_ASC : laf tăng dần
        array_multisort($keys, SORT_ASC,$mangdatsantruocngayhientai);
        return $mangdatsantruocngayhientai;
    }

    public function  addDatSan($request,int $id=null){
        $nam = substr($request->get('start_time'), 0, 4);
        $thang = substr($request->get('start_time'), 5, 2);
        $ngay = substr($request->get('start_time'), 8, 2);
        $time = substr($request->get('start_time'), 11, 8);
        $idsan=$request->get('idsan');
        $mangdatsantruocngayhientai=DB::table('datsans')->where('start_time','<', substr(Carbon::now(), 0, 10))->get();
        if(count($mangdatsantruocngayhientai)!=0){
            $id=$mangdatsantruocngayhientai[0]->id;
        }
        if(count(DB::table('datsans')->whereDay('start_time', $ngay)->whereMonth('start_time', $thang)->whereYear('start_time', $nam)->whereTime('start_time','=',$time)-> where('idsan', '=', $idsan)->get())==0) {
            return DB::table('datsans')::updateOrCreate(
                [
                    'id' => $id
                ],
                [
                    'idsan'=> $request->get('idsan'),
                    'iduser'=> $request->get('iduser'),
                    'start_time' => $request->get('start_time'),
                    'price' => $request->get('price'),
                ]

            );
        }
        return [];            
    }
}
class datsan
{
    public $id;
    public $nameQuan;
    public $addressQuan;
    public $phoneQuan;
    public $nameSan;
    public $time;
    public $price;
    public function __construct($id, $nameQuan, $addressQuan, $phoneQuan,$nameSan,$time,$price){
        $this->id = $id;
        $this->nameQuan = $nameQuan;
        $this->addressQuan = $addressQuan;
        $this->phoneQuan = $phoneQuan;
        $this->nameSan = $nameSan;
        $this->time = $time;
        $this->price = $price;
    }   
}
