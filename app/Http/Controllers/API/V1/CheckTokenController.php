<?php

namespace App\Http\Controllers\API\V1;

use Symfony\Component\HttpFoundation\Response;
use App\Services\CheckTokenService;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Services\SanService;
use App\Services\QuanService;
use Illuminate\Support\Facades\Validator;
use App\Models\Models\DatSan;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;


class CheckTokenController extends Controller
{
    protected $checkTokenService;
    protected $sanService;
    protected $quanService;
    public function __construct(CheckTokenService $checkTokenService,SanService $sanService, QuanService $quanService)
    {
        $this->checkTokenService = $checkTokenService;
        $this->sanService = $sanService;
        $this->quanService = $quanService;
    }
    public function thu(Request $request)
    {
        $a=7;
        return $a/4;
        return mt_rand(0, 2) ;

        return
        DB::table('datsans')->whereIn('idsan', [4])->whereDay('start_time', 13)->whereMonth('start_time', 10)->whereYear('start_time', 2021)->get();
        date_default_timezone_set("Asia/Ho_Chi_Minh");
        $time= date('Y-m-d h:i:s');
        return $this->quanService->findById(1);        
        if ($request->get('time')>$time) {
            return $time;
         } else {
            return 0;
    
        }
        
        $today = date('Y-m-d');
        $week = strtotime(date("Y-m-d", strtotime($today)) . " -1 week");
        $week=strftime("%Y-%m-%d", $week);

        //$listds=DatSan::where()
        return response()->json([
            'status' => $today,
            'code' => $week
        ]);
    }
   
    public function checkTokenUser(Request $request)
    {
        try {
            $checkToken= $this->checkTokenService->checkTokenUser($request);
            if (count($checkToken)>0) {
                $user=new User($checkToken[0]->id, $checkToken[0]->name, $checkToken[0]->phone, $checkToken[0]->gmail, $checkToken[0]->address);
                return response()->json([
                    'status' => true,
                    'code' => Response::HTTP_OK,
                    'user' => $user
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
                'message' => "token user sai"
            ]);
        }
    }
    public function checkTokenInnkeeper(Request $request)
    {
        try {
            $checkToken = $this->checkTokenService->checkTokenInnkeeper($request);
            if (count($checkToken) > 0) {
                $user = new User($checkToken[0]->id, $checkToken[0]->name, $checkToken[0]->phone, $checkToken[0]->gmail, $checkToken[0]->address);
                return response()->json([
                    'status' => true,
                    'code' => Response::HTTP_OK,
                    'innkeeper' => $user
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
                'message' => $e->getMessage(),
            ]);
        }
    }
    public function checkTokenAdmin(Request $request)
    {
        
        try {
            $checkToken = $this->checkTokenService->checkTokenAdmin($request);
            if (count($checkToken) > 0) {
                $user = new User($checkToken[0]->id, $checkToken[0]->name, $checkToken[0]->phone, $checkToken[0]->gmail, $checkToken[0]->address);
                return response()->json([
                    'status' => true,
                    'code' => Response::HTTP_OK,
                    'admin' => $user
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
                'message' => "token user sai"
            ]);
        }
    }
    public function checkTokenInnkeeperAndIdquan(Request $request)
    {
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

            $checkToken = $this->checkTokenService->checkTokenInnkeeper($request);
            if (count($checkToken) > 0) {
                $quan = $this->quanService->findById($request->get('idquan'));
                if($quan->phone!=$checkToken[0]->phone) {
                    return response()->json([
                        'status' => false,
                        'code' => Response::HTTP_INTERNAL_SERVER_ERROR,
                        'message' => "bạn không có quyền truy cập đến quán này"
                    ]);    
                }
                $user = new User($checkToken[0]->id, $checkToken[0]->name, $checkToken[0]->phone, $checkToken[0]->gmail, $checkToken[0]->address);
                return response()->json([
                    'status' => true,
                    'code' => Response::HTTP_OK,
                    'innkeeper' => $user,
                    'quan' =>$quan
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
                'message' => $e->getMessage(),
            ]);
        }
    }

    public function checkTokenInnkeeperAndIdsan(Request $request)
    {
        try {
            $checkToken = $this->checkTokenService->checkTokenInnkeeper($request);
            if (count($checkToken) > 0) {
                $san=$this->sanService->findById($request->get("idsan"));
                if(!$san) {
                    return response()->json([
                        'status' => false,
                        'code' => Response::HTTP_INTERNAL_SERVER_ERROR,
                        'message' => "id san không tồn tại"
                    ]);
                    
                }
                $quan= $this->quanService->findById($san->idquan);
                if($quan->phone!=$checkToken[0]->phone) {
                    return response()->json([
                        'status' => false,
                        'code' => Response::HTTP_INTERNAL_SERVER_ERROR,
                        'message' => "token không quyền truy cập"
                    ]);
        
                }
                $user = new User($checkToken[0]->id, $checkToken[0]->name, $checkToken[0]->phone, $checkToken[0]->gmail, $checkToken[0]->address);
                return response()->json([
                    'status' => true,
                    'code' => Response::HTTP_OK,
                    'innkeeper' => $user
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
                'message' => $e->getMessage(),
            ]);
        }
    }
 
}
class User
{
    public $id;
    public $name;
    public $phone;
    public $gmail;
    public $address;
    public function __construct($id, $name, $phone, $gmail, $address)
    {
        $this->id = $id;
        $this->name = $name;
        $this->phone = $phone;
        $this->gmail = $gmail;
        $this->address = $address;
    }
}