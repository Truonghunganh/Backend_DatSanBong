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

    // show là add data lên (để thêm vào)
    public function store(Request $request)
    {
    
        return $request;
        try {
            return response()->json([
                'status'  => true,
                'code'    => Response::HTTP_OK,
                'datsan' =>$this->datSanService->addDatSan($request)

            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'code' => Response::HTTP_INTERNAL_SERVER_ERROR,
                'message' => $e->getMessage()
            ]);
        }
    }
    public function show($id){
        try {
            return response()->json([
                'status' => true,
                'code' => Response::HTTP_OK,
                'datsans' => $this->datSanService->getListDatSanByIduser($id)
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
