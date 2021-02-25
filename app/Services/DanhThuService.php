<?php

namespace App\Services;

use Tymon\JWTAuth\Facades\JWTAuth;
use Illuminate\Support\Facades\Auth;
use App\Models\Models\DanhThu;

use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class DanhThuService
{
    public function getDanhThuByInnkeeper($request){
        
        $idquan=$request->get("idquan");
        $nam = substr($request->get('time'), 0, 4);
        $thang = substr($request->get('time'), 5, 2);
        if (!is_int((int)$nam)||!is_int((int)$thang)) {
            return [];
        }
        $danhthuold= DanhThu::where("idquan",$idquan)->whereYear("time",$nam)->whereMonth("time",$thang)->get();
        $danhthus=[];
        for ($i=0; $i < $danhthuold->count(); $i++) { 
            array_push($danhthus,new DanhThus($danhthuold[$i]->id,$danhthuold[$i]->idquan,$danhthuold[$i]->danhthu,$danhthuold[$i]->time));
        }
        $keys = array_column($danhthus, 'time');
        // SORT_ASC : laf tăng dần
        array_multisort($keys, SORT_ASC, $danhthus);
        return $danhthus;
                
    }
    
}
class DanhThus
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
