<?php

namespace App\Http\Controllers\API\V1;

use App\Models\Models\Quan;
use Symfony\Component\HttpFoundation\Response;
use App\Services\QuanService;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class QuanController extends Controller
{
    protected   $quanService;
    public function __construct(QuanService $quanService){
        $this->quanService = $quanService;
    }
    public function index()
    {
        try {
            return response()->json([
                'status' => true,
                'code' => Response::HTTP_OK,
                'quan' => Quan::all(),
            ]);
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
