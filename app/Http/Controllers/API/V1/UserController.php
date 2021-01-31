<?php


namespace App\Http\Controllers\API\V1;

use Symfony\Component\HttpFoundation\Response;
use App\Services\UserService;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Services\CheckTokenService;

class UserController extends Controller
{
    protected $userService;
    protected $checkTokenService;
    public function __construct(UserService $userService,CheckTokenService $checkTokenService)
    {
        $this->userService = $userService;
        $this->checkTokenService = $checkTokenService;
    }
    public function logout(){
        Auth::logout();
    }
    public function register(Request $request){
        
        try {
            $user= $this->userService->registerUser($request);
            if ($user===1) {
                return response()->json([
                    'status' => false,
                    'code' => Response::HTTP_INTERNAL_SERVER_ERROR,
                    'message' => "số điên thoại đã tôn tại không thể đăng ký được"
                ]);    
            }
            return response()->json([
                'status' => true,
                'code' => Response::HTTP_OK,
                'message' => "bạn đã đăng kí thành công "
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'code' => Response::HTTP_INTERNAL_SERVER_ERROR,
                'message' => $e->getMessage()
            ]);
        }
    }

    public function loginUser(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'phone' => 'required|min:8',
                'password' => 'required|min:8',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'status' => false,
                    'code' => Response::HTTP_INTERNAL_SERVER_ERROR,
                    'message' => $validator->errors()
                ]);
            }
            $login=[
                'role' => 'user',
                'phone'=>$request->get('phone'),
                'password'=> $request->get('password')
            ];
            if (Auth::attempt($login)) {
                return response()->json([
                    'status' => true,
                    'code' => Response::HTTP_OK,
                    'message' => "đăng nhập thành công ",
                    'token' =>$this->userService->getTokenUser($request,"user")
                ]);
            }
            else {
                return response()->json([
                    'u'=>7,
                    'status' => false,
                    'code' => Response::HTTP_INTERNAL_SERVER_ERROR,
                    'message' => "đăng nhập thất bại"
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
    public function checkTokenUser(Request $request){
        try {
            $checktoken = $this->checkTokenService->checkTokenUser($request);
        
            if (count($checktoken)> 0) {
                return response()->json([
                    'status' => true,
                    'code' => Response::HTTP_OK,
                    'message' => "token user đúng",
                ]);
            } else {
                return response()->json([
                    'status' => false,
                    'code' => Response::HTTP_INTERNAL_SERVER_ERROR,
                    'message' => "token user sai"
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
    public function editUserByToken(Request $request){
        try {
            $validator = Validator::make($request->all(), [
                'name' => 'required',
                'gmail' => 'required',
                'address' =>'required',
                'password' => 'required|min:8',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'status' => false,
                    'code' => Response::HTTP_INTERNAL_SERVER_ERROR,
                    'message' => $validator->errors()
                ]);
                
            }
            $checktoken = $this->checkTokenService->checkTokenUser($request);
            if (count($checktoken) > 0) {
                return response()->json([
                    'status' => true,
                    'code' => Response::HTTP_OK,
                    'token' => $this->userService->editUserByToken($request,$checktoken[0]->id)
                ]);
            } else {
                return response()->json([
                    'status' => false,
                    'code' => Response::HTTP_INTERNAL_SERVER_ERROR,
                    'message' => "token not found"
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
