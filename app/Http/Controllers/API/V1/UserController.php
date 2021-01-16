<?php

use Prettus\Repository\Contracts\RepositoryInterface;

namespace App\Http\Controllers\API\V1;

use Symfony\Component\HttpFoundation\Response;
use App\Services\UserService;
//use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Auth;

use App\Models\Models\User;
//use Auth;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Requests\UserLoginRequest;
class UserController extends Controller
{
    protected $userService;
    public function __construct(UserService $userService)
    {
        $this->userService = $userService;
    }
    public function index(Request $request)
    {
        try {
            return response()->json([
                'status' => true,
                'code' => Response::HTTP_OK,
                'datsans' => $this->userService->getListDatSanByIduser($request)
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'code' => Response::HTTP_INTERNAL_SERVER_ERROR,
                'message' => $e->getMessage()
            ]);
        }
    }
    public function login(Request $request)
    {
        //try {
            $login=[
                'gmail'=>$request->get('gmail'),
               'password'=> $request->get('password'),
                
            ];
            if (Auth::attempt($login)) {
                return response()->json([
                    'status' => true,
                    'code' => Response::HTTP_OK,
                    'user' => "đăng nhập thành công "
                ]);
            }
            else {
                return response()->json([
                    'status' => false,
                    'code' => Response::HTTP_INTERNAL_SERVER_ERROR,
                    'user' => "đăng nhập thất bại"
                ]);    
            }
    //     } catch (\Exception $e) {
    //         return response()->json([
    //             'status' => false,
    //             'code' => Response::HTTP_INTERNAL_SERVER_ERROR,
    //             'message' => $e->getMessage()
    //         ]);
    //     }
     }
    
    public function show($id)
    {
        try {
            return response()->json([
                'status' => true,
                'code' => Response::HTTP_OK,
                'user' => $this->userService->getUserById($id)
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'code' => Response::HTTP_INTERNAL_SERVER_ERROR,
                'message' => $e->getMessage()
            ]);
        }
    }

    


}
