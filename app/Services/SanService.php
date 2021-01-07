<?php

namespace App\Services;

use Illuminate\Support\Facades\DB;

use App\Models\Models\San;

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
   
    public function getSansByIdquanVaNgay($request)
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
        
        //dd(substr( $request->get('start_time'),0,4));

        // if ($request->get('idquan')) {
        //     return San::query()->where('idquan', '=', $request->get('idquan'))->get();
        // }

        // return "không có dữ liệu";
    }
    
}
