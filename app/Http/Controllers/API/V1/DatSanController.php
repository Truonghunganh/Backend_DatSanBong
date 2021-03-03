<?php

namespace App\Http\Controllers\API\V1;

use Symfony\Component\HttpFoundation\Response;
use App\Services\DatSanService;
use App\Services\CheckTokenService;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Services\SanService;
use App\Services\QuanService;

class DatSanController extends Controller 
{
    protected $datSanService;
    protected $sanService;
    protected $checkTokenService;
    public function __construct(DatSanService $datSanService,CheckTokenService $checkTokenService,SanService $sanService, QuanService $quanService){
        $this->datSanService = $datSanService;
        $this->checkTokenService = $checkTokenService;
        $this->sanService = $sanService;
        $this->quanService = $quanService;
    }

    // show là add data lên (để thêm vào)
    public function store(Request $request)
    {
       try {
            $tonkenUser=$this->checkTokenService->checkTokenUser($request);
            if(count($tonkenUser)> 0){
                date_default_timezone_set("Asia/Ho_Chi_Minh");
                $time = date('Y-m-d h:i:s');

                if ($request->get('start_time') < $time) {
                    return response()->json([
                        'status' => false,
                        'code' => Response::HTTP_INTERNAL_SERVER_ERROR,
                        'message' => "bạn phải đặt Trước thời gian hiện tại",
                    ]);

                }
        
                $datsan = $this->datSanService->addDatSan($request);
                if ($datsan) {
                    return response()->json([
                        'status'  => true,
                        'code'    => Response::HTTP_OK,
                        'datsan' => $datsan
                    ]);
                }else {
                    return response()->json([
                        'status' => false,
                        'code' => Response::HTTP_INTERNAL_SERVER_ERROR,
                        'message' => "bạn đã đặt sân thất bại"
                    ]);

                    
                }    
            }
            else {
                return response()->json([
                    'status' => false,
                    'code' => Response::HTTP_INTERNAL_SERVER_ERROR,
                    'message' => "token bị sai"
                ]);

            }
        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'code' => Response::HTTP_INTERNAL_SERVER_ERROR,
                'message' => $e->getMessage()
            ]);
        }
    }
    public function getListDatSanByUserToken(Request $request){
        try {
            $userbyToken=$this->checkTokenService->checkTokenUser($request);
            if (count($userbyToken)>0) {
                $id=$userbyToken[0]->id;
                $datsans= $this->datSanService->getListDatSanByIduser($id);
                if (count($datsans)==0) {
                    return response()->json([
                        'status' => false,
                        'code' => Response::HTTP_INTERNAL_SERVER_ERROR,
                        'message' => "id user không tồn tại"
                    ]);    
                }
                return response()->json([
                    'status' => true,
                    'code' => Response::HTTP_OK,
                    'datsans' => $datsans
                ]);

            }
            else{
                return response()->json([
                    'status' => false,
                    'code' => Response::HTTP_INTERNAL_SERVER_ERROR,
                    'message' => "token user false"
                ]);    
            }
        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'code' => Response::HTTP_INTERNAL_SERVER_ERROR,
                'message' => $e->getMessage()
            ]);
        }
    }
    public function getListDatSanByInnkeeper(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'start_time' => 'required',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'status' => false,
                    'code' => Response::HTTP_INTERNAL_SERVER_ERROR,
                    'message' => $validator->errors()
                ]);
            }

            $innkeeper = $this->checkTokenService->checkTokenInnkeeper($request);
            if (count($innkeeper) > 0) {
                $datsans = $this->datSanService->getListDatSanByInnkeeper($innkeeper,$request->get("start_time"));
                if (count($datsans) == 0) {
                    return response()->json([
                        'status' => false,
                        'code' => Response::HTTP_INTERNAL_SERVER_ERROR,
                        'message' => "id user không tồn tại"
                    ]);
                }
                return response()->json([
                    'status' => true,
                    'code' => Response::HTTP_OK,
                    'datsans' => $datsans
                ]);
            } else {
                return response()->json([
                    'status' => false,
                    'code' => Response::HTTP_INTERNAL_SERVER_ERROR,
                    'message' => "token user false"
                ]);
            }
        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'code' => Response::HTTP_INTERNAL_SERVER_ERROR,
                'message' => $e->getMessage()
            ]);
        }
    }

    public function destroy(Request $request,$id){
        try {
            $user = $this->checkTokenService->checkTokenUser($request);
            if (count($user) > 0) {
                $datsan = $this->datSanService->find($id);
                if (!$datsan) {
                    return response()->json([
                        'status' => false,
                        'code' => Response::HTTP_INTERNAL_SERVER_ERROR,
                        'message' => "không tìm thấy id đặt sân này "
                    ]);
                }
                
                if ($user[0]->id != $datsan->iduser) {
                    return response()->json([
                        'status' => false,
                        'code' => Response::HTTP_INTERNAL_SERVER_ERROR,
                        'message' => "token này không có quyền tri cập đến đặt sân này"
                    ]);
                }
                date_default_timezone_set("Asia/Ho_Chi_Minh");
                $time = date('Y-m-d H:i:s');
                if($time>$datsan->start_time){
                    return response()->json([
                        'status' => false,
                        'code' => Response::HTTP_INTERNAL_SERVER_ERROR,
                        'message' => "không thể xóa được vì thời gian đặt sân phải lớn hơn thời gian hiền tại"
                    ]);
                }
                $week = strtotime(date("Y-m-d", strtotime(date('Y-m-d'))) . " -1 week");
                $week = strftime("%Y-%m-%d", $week)." 00:00:00";
                
                $ds= $this->datSanService->updateDatsan($id,$week);
                if ($ds) {
                    return response()->json([
                        'status' => true,
                        'code' => Response::HTTP_OK,
                        'message' => "xóa thành công đặt sân",
                    ]);
                } else {
                    return response()->json([
                        'status' => false,
                        'code' => Response::HTTP_INTERNAL_SERVER_ERROR,
                        'message' => "Xóa đặt sân thất bại"
                    ]);
                }
                
            } else {
                return response()->json([
                    'status' => false,
                    'code' => Response::HTTP_INTERNAL_SERVER_ERROR,
                    'message' => "token user false"
                ]);
            }
        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'code' => Response::HTTP_INTERNAL_SERVER_ERROR,
                'message' => $e->getMessage()
            ]);
        }
    }
    public function xacNhanDatsanByInnkeeper(Request $request){
        try {
            $validator = Validator::make($request->all(), [
                'iddatsan' => 'required',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'status' => false,
                    'code' => Response::HTTP_INTERNAL_SERVER_ERROR,
                    'message' => $validator->errors()
                ]);
            }

            $innkeeper = $this->checkTokenService->checkTokenInnkeeper($request);
            if (count($innkeeper) > 0) {
                $datsan=$this->datSanService->getDatSanById($request->get('iddatsan'),false);
                if(!$datsan) {
                    return response()->json([
                        'status' => false,
                        'code' => Response::HTTP_INTERNAL_SERVER_ERROR,
                        'message' => "không tìm thấy id đặt sân này hoặt đặt sân này đã xác nhận rồi"
                    ]);
                }
                $san=$this->sanService->findById($datsan->idsan);
                if (!$san) {
                    return response()->json([
                        'status' => false,
                        'code' => Response::HTTP_INTERNAL_SERVER_ERROR,
                        'message' => "không tìm thấy id sân này = ".$datsan->idsan
                    ]);
                }
                
                $quan = $this->quanService->findById($san->idquan);
                if (!$quan) {
                    return response()->json([
                        'status' => false,
                        'code' => Response::HTTP_INTERNAL_SERVER_ERROR,
                        'message' => "không tìm thấy id quán này = ".$san->idquan
                    ]);
                }
                
                if ($innkeeper[0]->phone != $quan->phone) {
                    return response()->json([
                        'status' => false,
                        'code' => Response::HTTP_INTERNAL_SERVER_ERROR,
                        'message' => "token này không có quyền tri cập đến quán này"
                    ]);
                }
                $xacnhan = $this->datSanService->xacNhanDatsan($request->get('iddatsan'),1,$datsan->start_time,$datsan->price,$san);
                if ($xacnhan) {
                    return response()->json([
                        'status' => true,
                        'code' => Response::HTTP_OK,
                        'message' =>"xác nhận thành công",
                    ]);
                } else {
                    return response()->json([
                        'status' => false,
                        'code' => Response::HTTP_INTERNAL_SERVER_ERROR,
                        'message' => "xác nhận thất bại",
                    ]);
                }
                
            } else {
                return response()->json([
                    'status' => false,
                    'code' => Response::HTTP_INTERNAL_SERVER_ERROR,
                    'message' => "token user false"
                ]);
            }
        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'code' => Response::HTTP_INTERNAL_SERVER_ERROR,
                'message' => $e->getMessage()
            ]);
        }
    }

    public function getAllDatSanByInnkeeperAndIdquan(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'idquan' => 'required',
                'trangthai' => 'required',
                'time' => 'required'
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'status' => false,
                    'code' => Response::HTTP_INTERNAL_SERVER_ERROR,
                    'message' => $validator->errors()
                ]);
            }

            $innkeeper = $this->checkTokenService->checkTokenInnkeeper($request);
            if (count($innkeeper) > 0) {
                $quan= $this->quanService->findById($request->get('idquan'));
                if($innkeeper[0]->phone!=$quan->phone){
                    return response()->json([
                        'status' => false,
                        'code' => Response::HTTP_INTERNAL_SERVER_ERROR,
                        'message' => "token này không có quyền tri cập đến quán này"
                    ]); 
                }
                $datsans = $this->datSanService->getAllDatSanByIdquan($request->get("idquan"),$request->get("trangthai"),$request->get("time"));
                return response()->json([
                    'status' => true,
                    'code' => Response::HTTP_OK,
                    'datsans' => $datsans,
                    'quan'=>$quan
                ]);
            } else {
                return response()->json([
                    'status' => false,
                    'code' => Response::HTTP_INTERNAL_SERVER_ERROR,
                    'message' => "token user false"
                ]);
            }
        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'code' => Response::HTTP_INTERNAL_SERVER_ERROR,
                'message' => $e->getMessage()
            ]);
        }
    }

    public function thayDoiDatSanByInnkeeper(Request $request){
        try {
            $validator = Validator::make($request->all(), [
                'idsanOld' => 'required',
                'idsanNew' => 'required',
                'timeOld' => 'required',
                'timeNew' => 'required',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'status' => false,
                    'code' => Response::HTTP_INTERNAL_SERVER_ERROR,
                    'message' => $validator->errors()
                ]);
            }

            $token = $this->checkTokenService->checkTokenInnkeeper($request);
            if (count($token) > 0) {
                $sanOld = $this->sanService->findById($request->get('idsanOld'));
                $sanNew = $this->sanService->findById($request->get('idsanNew'));

                if (!$sanOld) {
                    return response()->json([
                        'status' => false,
                        'code' => Response::HTTP_INTERNAL_SERVER_ERROR,
                        'message' => "không tìm thấy sân có id = " . $request->get('idsanOld')
                    ]);
                }
                if (!$sanNew) {
                    return response()->json([
                        'status' => false,
                        'code' => Response::HTTP_INTERNAL_SERVER_ERROR,
                        'message' => "không tìm thấy sân có id = " . $request->get('idsanNew')
                    ]);
                }
                if (count($this->datSanService->checkdatsan($request->get('idsanOld'), $request->get('timeOld')))==0) {
                    return response()->json([
                        'status' => false,
                        'code' => Response::HTTP_INTERNAL_SERVER_ERROR,
                        'message' => "đặt sân củ chưa được đặt nên bạn không thể thay đổi "
                    ]);
                }
                if (count($this->datSanService->checkdatsan($request->get('idsanNew'), $request->get('timeNew'))) >0) {
                    return response()->json([
                        'status' => false,
                        'code' => Response::HTTP_INTERNAL_SERVER_ERROR,
                        'message' => "đặt sân mới được đặt nên bạn không thể thay đổi "
                    ]);
                }
                $quanOld = $this->quanService->findById($sanOld->idquan);
                $quanNew = $this->quanService->findById($sanNew->idquan);
                if (!$quanOld) {
                    return response()->json([
                        'status' => false,
                        'code' => Response::HTTP_INTERNAL_SERVER_ERROR,
                        'message' => "idquan không có"
                    ]);
                }
                if (!$quanNew) {
                    return response()->json([
                        'status' => false,
                        'code' => Response::HTTP_INTERNAL_SERVER_ERROR,
                        'message' => "idquan không có"
                    ]);
                }

                if ($token[0]->phone == $quanOld->phone&&$quanNew->phone == $quanOld->phone) {
                    $san = $this->datSanService->thayDoiDatSanByInnkeeper($request,$sanOld,$sanNew);
                    if ($san) {
                        return response()->json([
                            'status' => true,
                            'code' => Response::HTTP_OK,
                            'message' => "thay đổi đặt sân thành công",
                            'san' => $san
                        ]);
                    } else {
                        return response()->json([
                            'status' => false,
                            'code' => Response::HTTP_INTERNAL_SERVER_ERROR,
                            'message' => "thay đổi đặt sân thất bại"
                        ]);
                    }
                } else {
                    return response()->json([
                        'status' => false,
                        'code' => Response::HTTP_INTERNAL_SERVER_ERROR,
                        'message' => "token này không có quyền trong quán  này"
                    ]);
                }
                    
            } else {
                return response()->json([
                    'status' => false,
                    'code' => Response::HTTP_INTERNAL_SERVER_ERROR,
                    'message' => "token sai"
                ]);
            }
        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'code' => Response::HTTP_INTERNAL_SERVER_ERROR,
                'message' => $e->getMessage()
            ]);
        }
    }

}
