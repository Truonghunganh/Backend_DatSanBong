<?php

namespace App\Http\Controllers\API\V1;

use Symfony\Component\HttpFoundation\Response;
use App\Services\DatSanService;
use App\Services\CheckTokenService;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class DatSanController extends Controller 
{
    protected $datSanService;
    protected $checkTokenService;
    public function __construct(DatSanService $datSanService,CheckTokenService $checkTokenService){
        $this->datSanService = $datSanService;
        $this->checkTokenService = $checkTokenService;
    }

    // show là add data lên (để thêm vào)
    public function store(Request $request)
    {
       try {
            $datsan= $this->datSanService->addDatSan($request);
            if ($datsan) {
                return response()->json([
                    'status'  => true,
                    'code'    => Response::HTTP_OK,
                    'datsan' => $datsan
                ]);
            
            } else {
                return response()->json([
                    'status' => false,
                    'code' => Response::HTTP_INTERNAL_SERVER_ERROR,
                    'message' => "token bị sai"
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
    public function getListDatSanByUserToken(Request $request){
        try {
            $userbyToken=$this->checkTokenService->checkTokenUser($request);
            if (count($userbyToken)>0) {
                $id=$userbyToken[0]->id;
                return response()->json([
                    'status' => true,
                    'code' => Response::HTTP_OK,
                    'datsans' => $this->datSanService->getListDatSanByIduser($id)
                ]);

            }
            else{
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
                'message' => $e->getMessage()
            ]);
        }
    }


}
