<?php

namespace App\Http\Controllers\API\V1;


use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Services\DanhThuService;
use App\Services\QuanService;

use App\Services\CheckTokenService;
class DanhThuController extends Controller
{
    protected $danhThuService;
    protected $quanService;
    protected $checkTokenService;
    public function __construct(
        DanhThuService $danhThuService, 
        CheckTokenService $checkTokenService,
        QuanService $quanService
    )
    {
        $this->danhThuService = $danhThuService;
        $this->checkTokenService = $checkTokenService;
        $this->quanService = $quanService;
    }
    
    public function getDanhThuByInnkeeper(Request $request){
        try {
            $validator = Validator::make($request->all(), [
                'idquan' => 'required',
                'time' => 'required',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'status' => false,
                    'code' => Response::HTTP_INTERNAL_SERVER_ERROR,
                    'message' => $validator->errors()
                ]);
            }

            if (!is_int($request->get('idquan')) ) {
                return response()->json([
                    'status' => false,
                    'code' => Response::HTTP_INTERNAL_SERVER_ERROR,
                    'message' => "idquan yêu cầu phải là số"
                ]);
            }
            $token = $this->checkTokenService->checkTokenInnkeeper($request);
            if (count($token) > 0) {
                $quan = $this->quanService->findById($request->get('idquan'));
                if (!$quan) {
                    return response()->json([
                        'status' => false,
                        'code' => Response::HTTP_INTERNAL_SERVER_ERROR,
                        'message' => "idquan không tìm thấy"
                    ]);
                }
                if ($quan->phone!=$token[0]->phone) {
                    return response()->json([
                        'status' => false,
                        'code' => Response::HTTP_INTERNAL_SERVER_ERROR,
                        'message' => "bạn không có quyền truy cập đến id của quán này"
                    ]);
                }
                return response()->json([
                    'status'  => true,
                    'code'    => Response::HTTP_OK,
                    'danhthus' => $this->danhThuService->getDanhThuByInnkeeper($request)
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

    public function getDanhThuByAdmin(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'idquan' => 'required',
                'time' => 'required',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'status' => false,
                    'code' => Response::HTTP_INTERNAL_SERVER_ERROR,
                    'message' => $validator->errors()
                ]);
            }

            if (!is_int($request->get('idquan'))) {
                return response()->json([
                    'status' => false,
                    'code' => Response::HTTP_INTERNAL_SERVER_ERROR,
                    'message' => "idquan yêu cầu phải là số"
                ]);
            }
            $token = $this->checkTokenService->checkTokenAdmin($request);
            if (count($token) > 0) {
                $quan = $this->quanService->findById($request->get('idquan'));
                if (!$quan) {
                    return response()->json([
                        'status' => false,
                        'code' => Response::HTTP_INTERNAL_SERVER_ERROR,
                        'message' => "idquan không tìm thấy"
                    ]);
                }
                return response()->json([
                    'status'  => true,
                    'code'    => Response::HTTP_OK,
                    'danhthus' => $this->danhThuService->getDanhThuByInnkeeper($request)
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

    public function getDanhThuListQuanByAdmin(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'time' => 'required',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'status' => false,
                    'code' => Response::HTTP_INTERNAL_SERVER_ERROR,
                    'message' => $validator->errors()
                ]);
            }

            $token = $this->checkTokenService->checkTokenAdmin($request);
            if (count($token) > 0) {
                return response()->json([
                    'status'  => true,
                    'code'    => Response::HTTP_OK,
                    'danhthus' => $this->danhThuService->getDanhThuListQuanByAdmin($request)
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

}
