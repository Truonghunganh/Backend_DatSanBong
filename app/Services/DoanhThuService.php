<?php

namespace App\Services;

use Tymon\JWTAuth\Facades\JWTAuth;
use Illuminate\Support\Facades\Auth;
use App\Models\Models\DoanhThu;
use App\Models\Models\Quan;

use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class DoanhThuService
{
    public function getDanhThuByInnkeeper($request){
        
        $idquan=$request->get("idquan");
        $nam = substr($request->get('time'), 0, 4);
        $thang = substr($request->get('time'), 5, 2);
        if (!is_int((int)$nam)||!is_int((int)$thang)) {
            return [];
        }
        $danhthuold= DoanhThu::where("idquan",$idquan)->whereYear("time",$nam)->whereMonth("time",$thang)->get();
        $danhthus=[];
        for ($i=0; $i < $danhthuold->count(); $i++) { 
            array_push($danhthus,new DoanhThus($danhthuold[$i]->id,$danhthuold[$i]->idquan,$danhthuold[$i]->danhthu,$danhthuold[$i]->time));
        }
        $keys = array_column($danhthus, 'time');
        // SORT_ASC : laf tăng dần
        array_multisort($keys, SORT_ASC, $danhthus);
        return $danhthus;
                
    }
    public function getDanhThuListQuanByAdmin($request)
    {
        $nam = substr($request->get('time'), 0, 4);
        $thang = substr($request->get('time'), 5, 2);
        if (!is_int((int)$nam) || !is_int((int)$thang)) {
            return [];
        }
        $quans=Quan::where('trangthai',true)->get();
        $danhthus = [];
        for ($i = 0; $i < $quans->count(); $i++) {
            $tien=0;
            $danhthuold = DoanhThu::where("idquan", $quans[$i]->id)->whereYear("time", $nam)->whereMonth("time", $thang)->get();
            for ($j=0; $j <$danhthuold->count(); $j++) { 
                $tien+=(int)$danhthuold[$j]->danhthu;
            }
            array_push($danhthus, new DanhThuQuan($quans[$i]->id,$quans[$i]->name,$quans[$i]->address,$quans[$i]->phone,$tien));

        }
        $keys = array_column($danhthus, 'idquan');
        // SORT_ASC : laf tăng dần
        array_multisort($keys, SORT_ASC, $danhthus);
        return $danhthus;
    }
    
}

class DoanhThus
{
    public $id;
    public $idquan;
    public $danhthu;
    public $time;
    public function __construct($id, $idquan, $danhthu, $time)
    {
        $this->id = $id;
        $this->idquan = $idquan;
        $this->danhthu = $danhthu;
        $this->time = $time;
        
    }
}

class DanhThuQuan
{
    public $idquan;
    public $namequan;
    public $addressquan;
    public $phonequan;
    public $danhthu;
    public function __construct($idquan, $namequan,$addressquan,$phonequan, $danhthu)
    {
        $this->idquan = $idquan;
        $this->namequan = $namequan;
        $this->addressquan = $addressquan;
        $this->phonequan = $phonequan;
        $this->danhthu = $danhthu;
    }
}
