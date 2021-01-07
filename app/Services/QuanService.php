<?php

namespace App\Services;

use App\Models\Models\Quan;

class QuanService
{
    public function getQuanById($request)
    {
        if($request->get('id')){
            return Quan::where('id', $request->get('id'));
        }
        return null;
    }
    public function findById($id)
    {
        return Quan::find($id);
    }

}
