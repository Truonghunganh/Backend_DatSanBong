<?php

namespace App\Services;

use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\DB;

use App\Models\Models\Quan;
use Carbon\Carbon;

class QuanService
{
    public function getQuanById($request)
    {
        if($request->get('id')){
            return Quan::where('id', $request->get('id'))->get();
        }
        return [];
    }
    public function getAllQuan(){
        return Quan::all();
    }
    public function findById($id)
    {
        return Quan::find($id);
    }
    public function findQuanChuaduyetById($id)
    {
        return Quan::where('id', $id)->where('trangthai',0)->get();
    }
    
    public function getListQuansByTokenInnkeeper($innkeeper,$trangthai){
        return Quan::where('trangthai',$trangthai)->where('phone',$innkeeper[0]->phone)->get();
    }
    public function addQuanByInnkeeper($request, $token){
        
        $time = str_replace(' ', '_', Carbon::now());
        $nameImage=$token[0]->phone."_". str_replace(':', '_', $time). "_". $request->file('image')->getClientOriginalName();
        $file=$request->file('image');
        $file->move('image\Quan',$nameImage);
        return DB::insert(
            'insert into quans (name,image,address,phone,linkaddress,trangthai,Create_time) values (?,?, ?,?, ?,?,?)',
            [
                $request->get('name'),
                "image/Quan/".$nameImage,
                $request->get('address'),
                $token[0]->phone,
                $request->get('linkaddress'),
                0,
                Carbon::now()

            ]
        );
        
    }
    public function test(){
        File::delete('image/Quan/0987654321_2021-02-08_02_18_30.jpg');
    }
    public function editQuanByTokenInnkeeper($request, $getQuanById){
        if ($request->hasFile('image')) {
            File::delete($getQuanById[0]->image); // xóa hình củ đi
            $time = str_replace(' ', '_', Carbon::now());
            $nameImage = $getQuanById[0]->phone . "_" . str_replace(':', '_', $time). "_" . $request->file('image')->getClientOriginalName();
            $file = $request->file('image');
            $file->move('image\Quan', $nameImage);// thêm hình mới 
        
            return DB::update(
                'update quans set name=?,image=?,address=?,linkaddress=?,Create_time=? where id = ?',
                [
                    $request->get('name'),
                    "image/Quan/" . $nameImage,
                    $request->get('address'),
                    $request->get('linkaddress'),
                    Carbon::now(),
                    $request->get('id')

                ]
            );
        
        } else {
            
            return DB::update(
                'update quans set name=?,address=?,linkaddress=?,Create_time=? where id = ?',
                [
                    $request->get('name'),
                    $request->get('address'),
                    $request->get('linkaddress'),
                    Carbon::now(),
                    $request->get('id')

                ]
            );
        
        }
        
    }
    
    public function getQuanByPhone($phone){
        return Quan::where('phone',$phone)->get();
    }
    public function deleteQuanById($idquan)
    {
        $quan=Quan::find($idquan);
        if (File::delete($quan[0]->image)) {
            Quan::find($idquan)->delete();
            return true;            
        }
        return false;
    }

}
