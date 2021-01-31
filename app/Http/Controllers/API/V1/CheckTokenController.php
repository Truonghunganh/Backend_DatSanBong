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
            $checkTokenUser= $this->checkTokenService->checkTokenUser($request);
            if (count($checkTokenUser)>0) {
                $user=new User($checkTokenUser[0]->id, $checkTokenUser[0]->name, $checkTokenUser[0]->phone, $checkTokenUser[0]->gmail, $checkTokenUser[0]->address);
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