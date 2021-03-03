<?php

namespace App\Http\Controllers\API\V1;

use Carbon\Carbon;
use Symfony\Component\HttpFoundation\Response;
use App\Services\QuanService;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Services\CheckTokenService;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\File;

class QuanController extends Controller
{
    protected   $quanService;
    protected $checkTokenService;
    public function __construct(QuanService $quanService,CheckTokenService $checkTokenService){
        $this->quanService = $quanService;
        $this->checkTokenService = $checkTokenService;
    }
    public function index(Request $request)
    { 
       try {
           $checkTokenUser=$this->checkTokenService->checkTokenUser($request);
           if (count($checkTokenUser) > 0) {
                return response()->json([
                    'status' => true,
                    'code' => Response::HTTP_OK,
                    'quans' =>$this->quanService->getListQuansByTrangthai(1) 
                ]);   
           } else {
                return response()->json([
                    'status' => false,
                    'code' => Response::HTTP_INTERNAL_SERVER_ERROR,
                    'message' =>"token sai"
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
    public function show(Request $request,$id)
    {
        try {
            if ($request->header('tokenUser')) {
                try {
                    $token = $this->checkTokenService->checkTokenUser($request);
                    
                    if (count($token) > 0) {
                        $quan= $this->quanService->findById($id);
                        if(!$quan) {
                            return response()->json([
                                'status' => false,
                                'code' => Response::HTTP_INTERNAL_SERVER_ERROR,
                                'message' => "không tìm thấy idquan =".$id
                            ]);    
                        }
                        return response()->json([
                            'status'  => true,
                            'code'    => Response::HTTP_OK,
                            'quan' => $quan,
                            'person'=>'user'
                        ]);
                    } else {
                        return response()->json([
                            'status' => false,
                            'code' => Response::HTTP_INTERNAL_SERVER_ERROR,
                            'message' => "token user không đúng"
                        ]);
                    }
                } catch (\Exception $e1) {
                    return response()->json([
                        'status' => false,
                        'code' => Response::HTTP_INTERNAL_SERVER_ERROR,
                        'message' => $e1->getMessage()
                    ]);
                }
            }

            if ($request->header('tokenInnkeeper')) {
                try {
                    $token = $this->checkTokenService->checkTokenInnkeeper($request);
                    if (count($token) > 0) {
                        $quan = $this->quanService->findById($id);
                        if (!$quan) {
                            return response()->json([
                                'status' => false,
                                'code' => Response::HTTP_INTERNAL_SERVER_ERROR,
                                'message' => "không tìm thấy idquan =" . $id
                            ]);
                        }
                        return response()->json([
                            'status'  => true,
                            'code'    => Response::HTTP_OK,
                            'quan' => $quan,
                            'person' => 'innkeeper'
                        ]);
                    } else {
                        return response()->json([
                            'status' => false,
                            'code' => Response::HTTP_INTERNAL_SERVER_ERROR,
                            'message' => "token Innkeeper không đúng"
                        ]);
                    }
                } catch (\Exception $e1) {
                    return response()->json([
                        'status' => false,
                        'code' => Response::HTTP_INTERNAL_SERVER_ERROR,
                        'message' => $e1->getMessage()
                    ]);
                }
            }

            if ($request->header('tokenAdmin')) {
                try {
                    $token = $this->checkTokenService->checkTokenAdmin($request);
                    if (count($token) > 0) {
                        $quan = $this->quanService->findById($id);
                        if (!$quan) {
                            return response()->json([
                                'status' => false,
                                'code' => Response::HTTP_INTERNAL_SERVER_ERROR,
                                'message' => "không tìm thấy idquan =" . $id
                            ]);
                        }
                        return response()->json([
                            'status'  => true,
                            'code'    => Response::HTTP_OK,
                            'quan' => $quan,
                            'person' => 'admin'
                        
                        ]);
                    } else {
                        return response()->json([
                            'status' => false,
                            'code' => Response::HTTP_INTERNAL_SERVER_ERROR,
                            'message' => "token Admin không đúng"
                        ]);
                    }
                } catch (\Exception $e1) {
                    return response()->json([
                        'status' => false,
                        'code' => Response::HTTP_INTERNAL_SERVER_ERROR,
                        'message' => $e1->getMessage()
                    ]);
                }
            }
            return response()->json([
                'status' => false,
                'code' => Response::HTTP_INTERNAL_SERVER_ERROR,
                'message' => "không có token"
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'code' => Response::HTTP_INTERNAL_SERVER_ERROR,
                'message' => $e->getMessage()
            ]);
        }
    }
    public function getListQuansDaPheDuyetByTokenAdmin(Request $request)
    {
        try {
            $admin = $this->checkTokenService->checkTokenAdmin($request);
            if (count($admin) > 0) {

                return response()->json([
                    'status' => true,
                    'code' => Response::HTTP_OK,
                    'quans' =>  $this->quanService->getListQuansByTrangthai( 1)
                ]);
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
    public function destroy(Request $request,$id){
        try {
            $admin = $this->checkTokenService->checkTokenAdmin($request);
            if (count($admin) > 0) {
                $quan= $this->quanService->findById($id);
                if (!$quan) {
                    return response()->json([
                        'status' => false,
                        'code' => Response::HTTP_INTERNAL_SERVER_ERROR,
                        'message' => "không tìm thấy quán có id =".$id
                    ]);
                }

                if (!$this->quanService->deleteQuanByAdmin($id,$quan->image)) {
                    return response()->json([
                        'status' => false,
                        'code' => Response::HTTP_INTERNAL_SERVER_ERROR,
                        'message' => "xóa quán không thành công"
                    ]);
                }
                return response()->json([
                    'status' => true,
                    'code' => Response::HTTP_OK,
                    'message' =>"đã xóa quán thành công có id = " . $id
                ]);
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
    public function UpdateTrangThaiQuanTokenAdmin(Request $request)
    {
        try {
            
            $admin = $this->checkTokenService->checkTokenAdmin($request);
            if (count($admin) > 0) {
                $validator = Validator::make($request->all(), [
                    'trangthai' => 'required',
                    'idquan'=>'required'
                ]);

                if ($validator->fails()) {
                    return response()->json([
                        'status' => false,
                        'code' => Response::HTTP_INTERNAL_SERVER_ERROR,
                        'message' => $validator->errors()
                    ]);
                }

                $quan= $this->quanService->UpdateTrangThaiQuanTokenAdmin($request);
                if(!$quan){
                    return response()->json([
                        'status' => false,
                        'code' => Response::HTTP_INTERNAL_SERVER_ERROR,
                        'message' => "thay đổi trạng thái quán không thành công"
                    ]);
                }
                return response()->json([
                    'status' => true,
                    'code' => Response::HTTP_OK,
                    'quan' =>  $quan
                ]);
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
    
    public function getListQuansChuaPheDuyetByTokenAdmin(Request $request)
    {
        try {
            $admin = $this->checkTokenService->checkTokenAdmin($request);
            if (count($admin) > 0) {

                return response()->json([
                    'status' => true,
                    'code' => Response::HTTP_OK,
                    'quans' =>  $this->quanService->getListQuansByTrangthai(0)
                ]);
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
       
    public function getListQuansByTokenInnkeeper(Request $request){
        try {
            $innkeeper = $this->checkTokenService->checkTokenInnkeeper($request);
            if (count($innkeeper) > 0) {
                 
                return response()->json([
                    'status' => true,
                    'code' => Response::HTTP_OK,
                    'quans' =>  $this->quanService->getListQuansByTokenInnkeeper($innkeeper,1)                
                ]);
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
    public function getListQuansByTokenInnkeeperChuaPheDuyet(Request $request){
        try {
            $innkeeper = $this->checkTokenService->checkTokenInnkeeper($request);
            if (count($innkeeper) > 0) {

                return response()->json([
                    'status' => true,
                    'code' => Response::HTTP_OK,
                    'quans' =>  $this->quanService->getListQuansByTokenInnkeeper($innkeeper, 0)
                ]);
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
    public function deleteQuanChuaduyetByInnkeeper(Request $request){
        try {
            $validator = Validator::make($request->all(), [
                'idquan' => 'required',
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
                $quan= $this->quanService->findQuanChuaduyetById($request->get('idquan'));
                if (count($quan)==0) {
                    return response()->json([
                        'status' => false,
                        'code' => Response::HTTP_INTERNAL_SERVER_ERROR,
                        'message' => "không có idquan hoặc idquan này bạn không quyền  xóa"
                    ]);
                }
                if($token[0]->phone==$quan[0]->phone){
                    if ($this->quanService->deleteQuanById($request->get("idquan"))) {
                        return response()->json([
                            'status' => true,
                            'code' => Response::HTTP_OK,
                            'message' => "xóa thành  công có id quán là " . $request->get("idquan")
                        ]);
                    } else {
                        return response()->json([
                            'status' => false,
                            'code' => Response::HTTP_INTERNAL_SERVER_ERROR,
                            'message' => "xóa không thành công "
                        ]);
                    }
                    
                     
                }else {
                    return response()->json([
                        'status' => false,
                        'code' => Response::HTTP_INTERNAL_SERVER_ERROR,
                        'message' => "token không có quyền xóa quán này "
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
    public function addQuanByInnkeeper(Request $request){
        try {
            $validator = Validator::make($request->all(), [
                'name' => 'required',
                'address' => 'required',
                'image' => 'required',
                'linkaddress' => 'required',
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
                if ($request->hasFile('image')) {
                    $quan = $this->quanService->addQuanByInnkeeper($request, $token);
                    if ($quan) {
                        return response()->json([
                            'status' => true,
                            'code' => Response::HTTP_OK,
                            'message' => "add quan thành công"
                        ]);
                    } else {
                        return response()->json([
                            'status' => false,
                            'code' => Response::HTTP_INTERNAL_SERVER_ERROR,
                            'message' => "thêm thất bại"
                        ]);
                    }
                } else {
                    return response()->json([
                        'status' => false,
                        'code' => Response::HTTP_INTERNAL_SERVER_ERROR,
                        'message' => "không có image"
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
    public function editQuanByTokenInnkeeper(Request $request){
        try {
            $validator = Validator::make($request->all(), [
                'id' => 'required',
                'name' => 'required',
                'address' => 'required',
                'linkaddress' => 'required',
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
                $getQuanById=$this->quanService->getQuanById($request);
                if (count($getQuanById) > 0) {
                    if ($getQuanById[0]->phone==$token[0]->phone) {
                    
                        $quan = $this->quanService->editQuanByTokenInnkeeper($request, $getQuanById);
                        if ($quan) {
                            return response()->json([
                                'status' => true,
                                'code' => Response::HTTP_OK,
                                'message' => "chỉnh sữa thành công",
                                'quan'=>$quan
                            ]);
                        } else {
                            return response()->json([
                                'status' => false,
                                'code' => Response::HTTP_INTERNAL_SERVER_ERROR,
                                'message' => "chỉnh sữa thất bại",
                                'quan' => $quan
                            ]);
                        }    
                    } else {
                        return response()->json([
                            'status' => false,
                            'code' => Response::HTTP_INTERNAL_SERVER_ERROR,
                            'message' =>"token này không có quyền để chỉnh sữa quán đó"
                        ]);    
                    
                    }
                    
                }else {
                    return response()->json([
                        'status' => false,
                        'code' => Response::HTTP_INTERNAL_SERVER_ERROR,
                        'message' => "id đó không tồn tại"
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
