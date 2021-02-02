<?php

namespace App\Http\Controllers\API\V1;

use Carbon\Carbon;
use Symfony\Component\HttpFoundation\Response;
use App\Services\QuanService;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Services\CheckTokenService;

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
                    'quan' => $this->quanService->getAllQuan()
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
    public function show($id)
    {
        
        try {
            $quan=$this->quanService->findById($id);
            if($quan){
                return response()->json([
                'status'  => true,
                'code'    => Response::HTTP_OK,
                'quan' => $quan
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
