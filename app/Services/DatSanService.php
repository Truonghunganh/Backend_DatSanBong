<?php

namespace App\Services;

use Carbon\Carbon;
use App\Services\CheckTokenService;
use App\Models\Models\DanhThu;
use App\Services\QuanService;
use App\Services\SanService;

use Illuminate\Support\Facades\DB;
use App\Models\Models\DatSan;
use App\Models\Models\San;
class DatSanService
{
    protected $checkTokenService;
    protected $quanService;
    protected $sanService;
    public function __construct(CheckTokenService $checkTokenService,QuanService $quanService,SanService $sanService)
    {
        $this->checkTokenService = $checkTokenService;
        $this->quanService = $quanService;
        $this->sanService = $sanService;
    }

    public function getListDatSanByIduser($iduser)
    {
        $listdatsanByiduser= DB::table('datsans')->where('iduser', $iduser)->where('start_time','>=', Carbon::now())->get();
        $mangdatsantruocngayhientai=[];
        for ($i=0; $i < count($listdatsanByiduser); $i++) { 
            $san= DB::table('sans')->where('id','=', $listdatsanByiduser[$i]->idsan)->get();
            $quan= DB::table('quans')->where('id','=',$san[0]->idquan)->get();
            $datsan=new datsanS($listdatsanByiduser[$i]->id,$quan[0]->name,$quan[0]->address,$quan[0]->phone,$san[0]->name,$listdatsanByiduser[$i]->start_time,$listdatsanByiduser[$i]->price);
            array_push($mangdatsantruocngayhientai,$datsan);
        }
        $keys = array_column($mangdatsantruocngayhientai, 'time');
        // SORT_ASC : laf tăng dần
        array_multisort($keys, SORT_ASC,$mangdatsantruocngayhientai);
        return $mangdatsantruocngayhientai;
    }
    public function getDatSansByIdquanVaNgay($idquan,$start_time)
    { 
            $sansByIdquan = San::query()->where('idquan', '=',$idquan)->get();
            $datsans = array();
            $nam = substr($start_time, 0, 4);
            $thang = substr($start_time, 6, 2);
            $ngay = substr($start_time, 8, 2);
            foreach ($sansByIdquan as $san) {

                $ds = DB::table('datsans')->whereIn('idsan', [$san->id])->whereDay('start_time', $ngay)->whereMonth('start_time', $thang)->whereYear('start_time', $nam)->get();
                array_push($datsans, $ds);
            }
            return $datsans;
        
    }
    
    public function  addDatSan($request,int $id=null){
        try {
            $userbyToken=$this->checkTokenService->checkTokenUser($request);
            if (count($userbyToken) > 0) {
                $iduser = $userbyToken[0]->id;
                $nam = substr($request->get('start_time'), 0, 4);
                $thang = substr($request->get('start_time'), 5, 2);
                $ngay = substr($request->get('start_time'), 8, 2);
                $time = substr($request->get('start_time'), 11, 8);
                $idsan = $request->get('idsan');
                $san = $this->sanService->findById($idsan);
                       
                
                $mangdatsantruocngayhientai = DB::table('datsans')->where('start_time', '<', substr(Carbon::now(), 0, 10))->get();
                if (count($mangdatsantruocngayhientai) != 0) {
                    $id = $mangdatsantruocngayhientai[0]->id;
                }

                if (count(DB::table('datsans')->whereDay('start_time', $ngay)->whereMonth('start_time', $thang)->whereYear('start_time', $nam)->whereTime('start_time', '=', $time)->where('idsan', '=', $idsan)->get()) == 0) {
                    $danhthu= DB::table('danhthus')->whereDay('time', $ngay)->whereMonth('time', $thang)->whereYear('time', $nam)->where('idquan', '=', $san->idquan)->get();
                    
                    if (count($danhthu) >0) {
                        $priceNew=(int)$danhthu[0]->danhthu+(int)$request->get('price');
                        DB::update('update danhthus set danhthu=? where id = ?', [$priceNew,$danhthu[0]->id]);
                                
                    }else {
                        DB::insert('insert into danhthus (idquan, danhthu,time) values (?, ?,?)', [$san->idquan, $request->get('price'), substr($request->get('start_time'), 0, 10)." 00:00:00"]);
                    } 
                    return Datsan::updateOrCreate(
                        [
                            'id' => $id
                        ],
                        [
                            'idsan' => $request->get('idsan'),
                            'iduser' => $iduser,
                            'start_time' => $request->get('start_time'),
                            'price' => $request->get('price'),
                            'Create_time' => Carbon::now()
                        ]

                    );
                }
            }
            
        } catch (\Exception $e) {
            return [];
        }
        return [];            
    }
    public function thayDoiDatSanByInnkeeper($request, $sanOld, $sanNew){
        try {
            $datsan=DatSan::where("idsan",$request->get('idsanOld'))-> where("start_time", $request->get('timeOld'))->get();
            if (count($datsan)==0) {
                return false;
            }
            $nam = substr($request->get('timeOld'), 0, 4);
            $thang = substr($request->get('timeOld'), 5, 2);
            $ngay = substr($request->get('timeOld'), 8, 2);
            $danhthu = DB::table('danhthus')->whereDay('time', $ngay)->whereMonth('time', $thang)->whereYear('time', $nam)->where('idquan', '=', $sanOld->idquan)->get();

            if (count($danhthu) > 0) {
                $priceNew = (int)$danhthu[0]->danhthu - (int)$sanOld->price+(int)$sanNew->price;
                if ($priceNew == (int)$danhthu[0]->danhthu) {
                    if (DB::update('update datsans set idsan = ?,start_time=? where id = ?', [$request->get('idsanNew'), $request->get('timeNew'), $datsan[0]->id])) {
                        return true;
                    } 
                    
                }
                if(DB::update('update danhthus set danhthu=? where id = ?', [$priceNew, $danhthu[0]->id])){
                    if (DB::update('update datsans set idsan = ?,start_time=? where id = ?', [$request->get('idsanNew'), $request->get('timeNew'), $datsan[0]->id])) {
                        return true;
                    }else {
                        DB::update('update danhthus set danhthu=? where id = ?', [$danhthu[0]->danhthu, $danhthu[0]->id]);
                        return false; 
                    }
                }
                return DB::update('update danhthus set danhthu=? where id = ?', [$danhthu[0]->danhthu, $danhthu[0]->id]);
            }// else {
            //     DB::insert('insert into danhthus (idquan, danhthu,time) values (?, ?,?)', [$sanNew->idquan, $sanNew->price, substr($request->get('timeNew'), 0, 10) . " 00:00:00"]);
            // } 
                    
            
        } catch (\Exception $e) {
            return false;
        }
        return false;
    }
    public function checkdatsan($idsan,$start_time){
        return DatSan::where('idsan',$idsan)->where('start_time',$start_time)->get();
    }

    public function getListDatSanByInnkeeper($innkeeper,$start_time){
        $quans=$this->quanService->getQuanByPhoneDaduocduyet( $innkeeper[0]->phone);
        $datsans = array();
        
        foreach ($quans as $quan) {
            $sans= $this->sanService->getSansByIdquan($quan->id);
            $datsancuaquan=new datsancuaquan($quan->id,$quan->name,$quan->address,$quan->phone,$sans,$this->getDatSansByIdquanVaNgay($quan->id, $start_time));
            array_push($datsans,$datsancuaquan);           
        }
        return $datsans;
    }
}
class datsancuaquan
{
    public $id;
    public $name;
    public $address;
    public $phone;
    public $sans;
    public $datsans;
    
    public function __construct($id, $name, $address, $phone,$sans,$datsans){
        $this->id = $id;
        $this->name = $name;
        $this->address = $address;
        $this->phone = $phone;
        $this->sans = $sans;
        $this->datsans = $datsans;
    }   
}
class datsanS
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
