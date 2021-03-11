<?php

namespace App\Http\Controllers\API\V1;


use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Services\DoanhThuService;
use App\Services\QuanService;

use App\Services\CheckTokenService;
class DoanhThuController extends Controller
{
    protected $doanhThuService;
    protected $quanService;
    protected $checkTokenService;
    public function __construct(
        DoanhThuService $doanhThuService, 
        CheckTokenService $checkTokenService,
        QuanService $quanService
    )
    {
        $this->doanhThuService = $doanhThuService;
        $this->checkTokenService = $checkTokenService;
        $this->quanService = $quanService;
    }
    public function getDoanhThuCuaAdminTheoNam(Request $request){
        try {
            $validator = Validator::make($request->all(), [
                'nam' => 'required',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'status' => false,
                    'code' => Response::HTTP_INTERNAL_SERVER_ERROR,
                    'message' => $validator->errors()
                ]);
            }

            if (!is_int($request->get('nam'))) {
                return response()->json([
                    'status' => false,
                    'code' => Response::HTTP_INTERNAL_SERVER_ERROR,
                    'message' => "năm yêu cầu phải là số"
                ]);
            }
            $token = $this->checkTokenService->checkTokenAdmin($request);
            if (count($token) > 0) {
                return response()->json([
                    'status'  => true,
                    'code'    => Response::HTTP_OK,
                    'laixuat' =>"1%",
                    'doanhthustheonam' => $this->doanhThuService->getDoanhThuCuaAdminTheoNam($request->get('nam'))
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
    public function getDoanhThuByInnkeeper(Request $request){
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
                    'doanhthus' => $this->doanhThuService->getDoanhThuByInnkeeper($request)
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


    public function getTongDoanhThuTheoNamByInnkeeper(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'idquan' => 'required',
                'nam' => 'required',
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
                if ($quan->phone != $token[0]->phone) {
                    return response()->json([
                        'status' => false,
                        'code' => Response::HTTP_INTERNAL_SERVER_ERROR,
                        'message' => "bạn không có quyền truy cập đến id của quán này"
                    ]);
                }
                return response()->json([
                    'status'  => true,
                    'code'    => Response::HTTP_OK,
                    'doanhthustheonam' => $this->doanhThuService->getDoanhThuTheoNamByInnkeeper($request)
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
    public function getDoanhThuByAdmin(Request $request)
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
                    'doanhthus' => $this->doanhThuService->getDoanhThuByInnkeeper($request)
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

    public function getDoanhThuListQuanByAdmin(Request $request)
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
                    'doanhthus' => $this->doanhThuService->getDoanhThuListQuanByAdmin($request),
                    'laixuat'=>'1%'
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
