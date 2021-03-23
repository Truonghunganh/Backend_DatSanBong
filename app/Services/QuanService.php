<?php

namespace App\Services;

use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\DB;

use App\Models\Models\Quan;
use Carbon\Carbon;

class QuanService
{
    public function deleteQuanByAdmin($id,$image){
        File::delete($image);
        $quan=Quan::find($id);      
        return $quan->delete();
    }
    public function searchListQuans($search){
        return Quan::where('name','like','%' . $search.'%')->orwhere('address','like','%' . $search.'%')->orwhere('phone', 'like', '%' . $search . '%')->get();
    }

    public function getAllQuan(){
        return Quan::all();
    }
    public function findById($id)
    {
        return Quan::find($id);
    }
    public function findByIdVaTrangThai($id,$trangthai)
    {
        return Quan::where('id',$id)->where('trangthai',$trangthai)->first();
    }
    public function findQuanChuaduyetById($id)
    {
        return Quan::where('id', $id)->where('trangthai',0)->get();
    }
    public function getAllQuansByTrangthai($trangthai)
    {
        return Quan::where('trangthai', $trangthai)->get();
    }
    public function getListQuansByTrangthaiVaPage($trangthai,$soluong){
        return Quan::where('trangthai',$trangthai)->paginate($soluong);
    }
    public function getListQuansByTrangthai($trangthai,$iduser)
    {
        $quans= Quan::where('trangthai', $trangthai)->get();
        $quansnew=[];
        for ($i=0; $i <count($quans) ; $i++) { 
            $chonquan = DB::table('chonquans')->where("iduser", $iduser)->where('idquan',$quans[$i]->id)->first();
            $solan=0;
            if ($chonquan) {
                $solan=$chonquan->solan;
            }
            array_push($quansnew, new Quan1($quans[$i]->id,$quans[$i]->name,$quans[$i]->image,$quans[$i]->address,$quans[$i]->phone,$quans[$i]->linkaddress,$quans[$i]->vido,$quans[$i]->kinhdo,$quans[$i]->review,$solan));

        }
        $keys = array_column($quansnew, 'solan');
        array_multisort($keys, SORT_DESC, $quansnew);
        return $quansnew;
       

    }
    public function  UpdateTrangThaiQuanTokenAdmin($request){
        return DB::update('update quans set trangthai = ? where id =? ', [$request->get('trangthai'),$request->get('idquan')]);
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
            'insert into quans (name,image,address,phone,linkaddress,trangthai,vido,kinhdo,review,Create_time) values (?,?, ?,?, ?,?,? ,?,?,?)',
            [
                $request->get('name'),
                "image/Quan/".$nameImage,
                $request->get('address'),
                $token[0]->phone,
                $request->get('linkaddress'),
                0,
                $request->get('vido'),
                $request->get('kinhdo'),
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
    public function getQuanByPhoneDaduocduyet($phone)
    {
        return Quan::where('phone', $phone)->where('trangthai',1)->get();
    }
    public function getQuanByPhoneChuaduocduyet($phone)
    {
        return Quan::where('phone', $phone)->where('trangthai', 0)->get();
    }
    
    public function deleteQuanById($idquan)
    {
        $quan=Quan::find($idquan);
        if (File::delete($quan->image)) {
            Quan::find($idquan)->delete();
            return true;            
        }
        return false;
    }

}

class Quan1
{
    public $id;
    public $name;
    public $image;
    public $address;
    public $phone;
    public $linkaddress;
    public $vido;
    public $kinhdo;
    public $review;
    public $solan;
    
    public function __construct($id, $name, $image, $address, $phone, $linkaddress,$vido,$kinhdo,$review,$solan)
    {
        $this->id = $id;
        $this->name = $name;
        $this->image = $image;
        $this->address = $address;
        $this->phone= $phone;
        $this->linkaddress = $linkaddress;
        $this->vido = $vido;
        $this->kinhdo = $kinhdo;
        $this->review = $review;
        $this->solan = $solan;
    }
}
