<?php

namespace App\Http\Controllers\API\V1;

use Symfony\Component\HttpFoundation\Response;
use App\Services\SanService;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class SanController extends Controller
{
    protected $SanService;
    public function __construct(SanService $SanService)
    {
        $this->SanService = $SanService;
    }
    
    public function index(Request $request)
    {
        try {
            return response()->json([
                'status' => true,
                'code' => Response::HTTP_OK,
                'san' => $this->SanService->getSansByIdquan($request),
                'datsans'=> $this->SanService->getDatSansByIdquanVaNgay($request),
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
