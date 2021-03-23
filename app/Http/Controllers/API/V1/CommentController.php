<?php

namespace App\Http\Controllers\API\V1;

use App\Services\CheckTokenService;
use Illuminate\Support\Facades\Validator;
use Symfony\Component\HttpFoundation\Response;
use App\Services\QuanService;
use App\Services\ReviewService;
use App\Services\CommentService;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class CommentController extends Controller
{
    protected $checkTokenService;
    protected $quanService;
    protected $commentService;
    protected $reviewService;
    public function __construct(
        CheckTokenService $checkTokenService,
        QuanService $quanService,
        ReviewService $reviewService,
        CommentService $commentService
    ) {
        $this->checkTokenService = $checkTokenService;
        $this->quanService = $quanService;
         $this->commentService = $commentService;
         $this->reviewService = $reviewService;
    }
    public function index(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [

                'idquan' => 'required',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'status' => false,
                    'code' => Response::HTTP_INTERNAL_SERVER_ERROR,
                    'message' => $validator->errors()
                ]);
            }

            $tonkenUser = $this->checkTokenService->checkTokenUser($request);
            if ($tonkenUser) {
                $quan = $this->quanService->findByIdVaTrangThai($request->get('idquan'), 1);
                if (!$quan) {
                    return response()->json([
                        'status' => false,
                        'code' => Response::HTTP_INTERNAL_SERVER_ERROR,
                        'message' => "không tìm thấy quán này",
                    ]);
                }
                $comments = $this->commentService->getAllCommentsCuaMotQuan($quan->id, $tonkenUser);
                return response()->json([
                    'status'  => true,
                    'code'    => Response::HTTP_OK,
                    'comments' => $comments
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
    public function store(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
               'idquan' => 'required',
               'binhluan' => 'required'
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'status' => false,
                    'code' => Response::HTTP_INTERNAL_SERVER_ERROR,
                    'message' => $validator->errors()
                ]);
            }

            $tonkenUser = $this->checkTokenService->checkTokenUser($request);
            if ($tonkenUser) {
                $quan = $this->quanService->findByIdVaTrangThai($request->get('idquan'), 1);
                if (!$quan) {
                    return response()->json([
                        'status' => false,
                        'code' => Response::HTTP_INTERNAL_SERVER_ERROR,
                        'message' => "không tìm thấy quán này",
                    ]);
                }
                $comments = $this->commentService->addComment($quan->id, $tonkenUser, $request->get("binhluan"));
                return response()->json([
                    'status'  => true,
                    'code'    => Response::HTTP_OK,
                    'comments' => $comments
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
    public function show($id)
    {
        //
    }
    public function update(Request $request, $id)
    {
        try {
            $validator = Validator::make($request->all(), [
                'binhluan' => 'required'
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'status' => false,
                    'code' => Response::HTTP_INTERNAL_SERVER_ERROR,
                    'message' => $validator->errors()
                ]);
            }

            $tonkenUser = $this->checkTokenService->checkTokenUser($request);
            if ($tonkenUser) {
                $comment = $this->commentService->findById($id);
                if (!$comment) {
                    return response()->json([
                        'status' => false,
                        'code' => Response::HTTP_INTERNAL_SERVER_ERROR,
                        'message' => "không tim thấy",
                    ]);
                }
                $review = $this->reviewService->findById($comment->idreview);
                if (!$review) {
                    return response()->json([
                        'status' => false,
                        'code' => Response::HTTP_INTERNAL_SERVER_ERROR,
                        'message' => "không tim thấy",
                    ]);
                }
                if ($tonkenUser->id != $review->iduser) {
                    return response()->json([
                        'status' => false,
                        'code' => Response::HTTP_INTERNAL_SERVER_ERROR,
                        'message' => "bạn không có quán này",
                    ]);
                }
                $comments = $this->commentService->updateComment($id, $request->get("binhluan"), $review->idquan, $tonkenUser);
                return response()->json([
                    'status'  => true,
                    'code'    => Response::HTTP_OK,
                    'comments' => $comments
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
    public function destroy($id)
    {
        //
    }
}
    