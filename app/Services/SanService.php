<?php

namespace App\Services;

use Illuminate\Support\Facades\DB;

use App\Models\Models\San;
use Carbon\Carbon;

class SanService
{
    public function getSansByIdquan($request)
    {
        if ($request->get('idquan')) {
            return San::query()->where('idquan','=', $request->get('idquan'))->get();
        }
        
        return [];
    }
    public function findById($id)
    {
        return San::find($id);
    }
   
    public function getDatSansByIdquanVaNgay($request)
    {
        if ($request->get('idquan')) {
            $sanArray= San::query()->where('idquan', '=', $request->get('idquan'))->get();
            $datsans= array();
            $nam=substr( $request->get('start_time'),0,4);
            $thang=substr( $request->get('start_time'),6,2);
            $ngay=substr( $request->get('start_time'),8,2);
            $i=0;
            foreach ($sanArray as $san){
                
                $ds= DB::table('datsans')->whereIn('idsan', [$san->id])->whereDay('start_time', $ngay)->whereMonth('start_time',$thang)->whereYear('start_time',$nam)->get();
                array_push($datsans, $ds);
                
            }    
            return $datsans;
        }
        
    }
    public function addSanByInnkeeper($request){
        return DB::insert(
            'insert into sans (idquan,name,numberpeople,trangthai,priceperhour,Create_time) values (?,?, ?,?, ?,?)',
            [
                $request->get('idquan'),
                $request->get('name'),
                $request->get('numberpeople'),
                0,
                $request->get('priceperhour'),
                Carbon::now()

            ]
        );
        
    }

    public function editSanByInnkeeper($request)
    {
        return DB::update(
            'update sans set name = ?, numberpeople = ?, priceperhour =? where id=?',
            [
                $request->get('name'),
                $request->get('numberpeople'),
                $request->get('priceperhour'),
                $request->get('id'),
            ]
        );
    }
    
}
