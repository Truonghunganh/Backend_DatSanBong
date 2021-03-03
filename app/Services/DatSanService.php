<?php

namespace App\Services;

use Carbon\Carbon;
use App\Services\CheckTokenService;
use App\Models\Models\DanhThu;
use App\Services\QuanService;
use App\Services\SanService;
use App\Services\UserService;

use Illuminate\Support\Facades\DB;
use App\Models\Models\Quan;
use App\Models\Models\DatSan;
class DatSanService
{
    protected $checkTokenService;
    protected $quanService;
    protected $sanService;
    protected $userService;
    public function __construct(CheckTokenService $checkTokenService,QuanService $quanService,SanService $sanService,UserService $userService)
    {
        $this->checkTokenService = $checkTokenService;
        $this->quanService = $quanService;
        $this->sanService = $sanService;
        $this->userService = $userService;
    }
    public function updateDatsan($id, $time){
        return DB::update('update datsans set start_time = ? where id = ?', [$time, $id]);
    }
    public function find($id){
        return DatSan::find($id);
    }
    public function getDatSanById($id,$xacnhan){
        return DatSan::where('id',$id)->where('xacnhan',$xacnhan)->first();
    }
    public function xacNhanDatsan($id,$xacnhan,$start_time,$price,$san){
        $xacnhan=DB::update('update datsans set xacnhan = ? where id = ?', [$xacnhan,$id]);
        $nam = substr($start_time, 0, 4);
        $thang = substr($start_time, 5, 2);
        $ngay = substr($start_time, 8, 2);
             
        if ($xacnhan) {
           $doanhthu= DB::table('doanhthus')->whereDay('time', $ngay)->whereMonth('time', $thang)->whereYear('time', $nam)->where('idquan', '=', $san->idquan)->get();
            if (count($doanhthu)> 0) {
                $priceNew = (int)$doanhthu[0]->doanhthu + (int)$price;
                DB::update('update doanhthus set doanhthu=? where id = ?', [$priceNew, $doanhthu[0]->id]);
            } else {
                DB::insert('insert into doanhthus (idquan, doanhthu,time) values (?, ?,?)', [$san->idquan, $price, substr($start_time, 0, 10) . " 00:00:00"]);
            } 
            
            return true;        
                    
        } else {
            return false;
        }
                
    }
    public function getListDatSanByIduser($iduser)
    {
        $listdatsanByiduser= DB::table('datsans')->where('iduser', $iduser)->where('start_time','>=', Carbon::now())->get();
        $mangdatsantruocngayhientai=[];
        for ($i=0; $i < count($listdatsanByiduser); $i++) { 
            $san= DB::table('sans')->where('id','=', $listdatsanByiduser[$i]->idsan)->get();
            $quan= DB::table('quans')->where('id','=',$san[0]->idquan)->get();
            $datsan=new datsanS($listdatsanByiduser[$i]->id,$quan[0]->name,$quan[0]->address,$quan[0]->phone,$san[0]->name,$listdatsanByiduser[$i]->start_time,$san[0]->numberpeople,$listdatsanByiduser[$i]->price,$listdatsanByiduser[$i]->xacnhan);
            array_push($mangdatsantruocngayhientai,$datsan);
        }
        $keys = array_column($mangdatsantruocngayhientai, 'time');
        // SORT_ASC : laf tăng dần
        array_multisort($keys, SORT_ASC,$mangdatsantruocngayhientai);
        return $mangdatsantruocngayhientai;
    }
    public function getDatSansByIdquanVaNgay($idquan,$start_time)
    {
        $sanArray = $this->sanService->getSansByIdquan($idquan);
        $datsans = array();
        $nam = substr($start_time, 0, 4);
        $thang = substr($start_time, 6, 2);
        $ngay = substr($start_time, 8, 2);
        foreach ($sanArray as $san) {
            $datsan = DB::table('datsans')->whereIn('idsan', [$san->id])->whereDay('start_time', $ngay)->whereMonth('start_time', $thang)->whereYear('start_time', $nam)->get();
            $datsannews=array();
            foreach ($datsan as $ds) {
                array_push($datsannews, new DatSan1($ds->id, $ds->idsan, $ds->iduser, $ds->start_time, $ds->price, $ds->Create_time));    
            }
            $keys= array_column($datsannews,'start_time');
            array_multisort($keys,SORT_ASC,$datsannews);
            array_push($datsans, $datsannews);
        }
        return $datsans;
    }
    public function  addDatSan($request,int $id=null){
        try {
            $userbyToken=$this->checkTokenService->checkTokenUser($request);
            if (count($userbyToken) > 0) {
                $iduser = $userbyToken[0]->id;
                $idsan = $request->get('idsan');
                $week = strtotime(date("Y-m-d", strtotime(date('Y-m-d'))) . " -1 week");
                $week = strftime("%Y-%m-%d", $week);
                $mangdatsantruoc1tuan = DB::table('datsans')->where('start_time', '<', substr($week, 0, 10))->get();
                if (count($mangdatsantruoc1tuan) != 0) {
                    $id = $mangdatsantruoc1tuan[0]->id;
                }
                if (count(DB::table('datsans')->where('start_time', '=', $request->get('start_time'))->where('idsan', '=', $idsan)->get()) == 0) {
                    return Datsan::updateOrCreate(
                        [
                            'id' => $id
                        ],
                        [
                            'idsan' => $request->get('idsan'),
                            'iduser' => $iduser,
                            'start_time' => $request->get('start_time'),
                            'price' => $request->get('price'),
                            'xacnhan'=>false,
                            'Create_time' => Carbon::now()
                        ]

                    );
                }
            }
            
        } catch (\Exception $e) {
            return false;
        }
        return false;            
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

    public function getAllDatSanByIdquan($idquan,$xacnhan,$time){
        $sans=$this->sanService->getSansByIdquan($idquan);

        $datsansnew=[];
        foreach ($sans as $san) {
            $datsans=DatSan::where('idsan',$san->id)->where('xacnhan',$xacnhan)->where("start_time",">",$time)->get();
            foreach ($datsans as $datsan) {
                $user=$this->userService->getUserById($datsan->iduser);
                $ds=new Datsan2($datsan->id,$san,$user,$datsan->start_time,$datsan->price,$datsan->xacnhan);
                array_push($datsansnew,$ds);
            }

        }
        $keys = array_column($datsansnew, 'start_time');
        array_multisort($keys, SORT_ASC, $datsansnew);
        return $datsansnew;
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
class Datsan1
{
    public $id;
    public $idsan;
    public $iduser;
    public $start_time;
    public $price;
    public $Create_time;

    public function __construct($id, $idsan, $iduser, $start_time, $price, $Create_time)
    {
        $this->id = $id;
        $this->name = $idsan;
        $this->iduser = $iduser;
        $this->start_time = $start_time;
        $this->price = $price;
        $this->Create_time = $Create_time;

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
    public $numberpeople;
    public $price;
    public $xacnhan;
    public function __construct($id, $nameQuan, $addressQuan, $phoneQuan,$nameSan,$time, $numberpeople,$price,$xacnhan){
        $this->id = $id;
        $this->nameQuan = $nameQuan;
        $this->addressQuan = $addressQuan;
        $this->phoneQuan = $phoneQuan;
        $this->nameSan = $nameSan;
        $this->time = $time;
        $this->numberpeople = $numberpeople;
        $this->price = $price;
        $this->xacnhan = $xacnhan;
    }   
}
class Datsan2
{
    public $id;
    public $san;
    public $user;
    public $start_time;
    public $price;
    public $xacnhan;
    public function __construct($id, $san,$user, $start_time, $price, $xacnhan)
    {
        $this->id = $id;
        $this->san = $san;
        $this->user = $user;
        $this->start_time = $start_time;
        $this->price = $price;
        $this->xacnhan = $xacnhan;
    }
}
