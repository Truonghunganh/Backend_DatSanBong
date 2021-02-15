<?php

namespace App\Http\Controllers\API\V1;

use Symfony\Component\HttpFoundation\Response;
use App\Services\CheckTokenService;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class CheckTokenController extends Controller
{
    protected $checkTokenService;
    public function __construct(CheckTokenService $checkTokenService)
    {
        $this->checkTokenService = $checkTokenService;
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
            $checkToken = $this->checkTokenService->checkTokenInnkeeperAndIdquan($request);
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