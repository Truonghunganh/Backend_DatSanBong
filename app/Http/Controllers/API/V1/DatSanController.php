<?php

namespace App\Http\Controllers\API\V1;

use Symfony\Component\HttpFoundation\Response;
use App\Services\DatSanService;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class DatSanController extends Controller
{
    protected $datSanService;
    public function __construct(DatSanService $datSanService){
        $this->datSanService = $datSanService;
    }
    public function index(Request $request)
    {
       try {
            return response()->json([
                'status' => true,
                'code' => Response::HTTP_OK,
                'datsans' => $this->datSanService->getDatSanByIdSanVaNgay($request)
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
