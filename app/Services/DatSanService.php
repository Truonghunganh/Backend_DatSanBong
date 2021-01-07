<?php

namespace App\Services;

use Illuminate\Support\Facades\DB;
use App\Models\Models\DatSan;

class DatSanService
{

    public function getDatSanByIdSanVaNgay($request)
    {
        $query = DatSan::query();
        if($request->get("idsan")){
            //dd($request->get("idsan"));
            //return DatSan::all();
            return DB::table('datsans') -> whereIn('idsan', [1])-> whereDay('start_time','10')->get();
            //return $query->where('idsan','=', $request->get("idsan"))->get();
        }
        
        return false;
    
    }
}
