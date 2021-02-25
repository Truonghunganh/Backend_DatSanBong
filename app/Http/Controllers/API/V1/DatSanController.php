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
                $datsan = $this->datSanService->addDatSan($request);
                if ($datsan) {
                    return response()->json([
                        'status'  => true,
                        'code'    => Response::HTTP_OK,
                        'datsan' => $datsan
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
