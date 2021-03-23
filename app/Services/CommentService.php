<?php

namespace App\Services;

use Tymon\JWTAuth\Facades\JWTAuth;
//use JWTAuth;
use Illuminate\Support\Facades\Auth;
use App\Models\Models\Comment;

use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

use App\Services\UserService;
use App\Services\ReviewService;
class CommentService
{
    protected $reviewService;
    protected $userService;

    public function __construct(ReviewService $reviewService,UserService $userService){
        $this->reviewService = $reviewService;
        $this->userService = $userService;
    }


    public function getAllCommentsCuaMotQuan($idquan,$user){
        $commentsnew=[];
        $usernew=$user;
        $quyenUpdate=1;
        $reviews=$this->reviewService->getAllReviewByIdquan($idquan);
        for ($i=0; $i < count($reviews); $i++) { 
            if ($user->id != $reviews[$i]->iduser) {
                $usernew = $this->userService->getUserById($reviews[$i]->iduser);
                $quyenUpdate=0;
            }
            $comments = Comment::where('idreview', $reviews[$i]->id)->get();
            for ($j=0; $j <count($comments); $j++) {
                $comment = new Comment1($comments[$j]->id,$usernew->name, $reviews[$i]->review, $comments[$j]->Create_time, $comments[$j]->binhluan, $quyenUpdate);
                array_push($commentsnew, $comment);
            }
            $quyenUpdate=1;
            $usernew=$user;
        }
        $keys = array_column($commentsnew, 'Create_time');
        array_multisort($keys, SORT_DESC , $commentsnew);
        return $commentsnew;
        
    }
    public function addComment($idquan, $user,$binhluan){
        date_default_timezone_set("Asia/Ho_Chi_Minh");
        $time = date('Y-m-d h:i:s');
        $iduser=$user->id;
        
        $review= $this->reviewService->findReviewByIduserVaIdquan($iduser, $idquan);
        
        if (!$review) {
            $this->reviewService->addReview($iduser, $idquan, 0);
            $review = $this->reviewService->findReviewByIduserVaIdquan($iduser, $idquan);
        }
        DB::insert('insert into comments (idreview, binhluan,Create_time) values (?, ?,?)', [$review->id,$binhluan,$time ]);
        return $this->getAllCommentsCuaMotQuan($idquan,$user);
    }
    public function findById($id){
        return Comment::find($id);
    }
    public function updateComment($id,$binhluan,$idquan,$user){
        DB::update('update comments set binhluan = ? where id = ?', [$binhluan,$id]);
        return $this->getAllCommentsCuaMotQuan($idquan, $user);
        
    }
}

class Comment1{
    public $id;
    public $tenUser;
    public $review;
    public $Create_time;
    public $binhluan;
    public $quyenUpdate;
    public function __construct($id,$tenUser, $review, $Create_time, $binhluan, $quyenUpdate){
        $this->id = $id;
        $this->tenUser = $tenUser;
        $this->review = $review;
        $this->Create_time = $Create_time;
        $this->binhluan = $binhluan;
        $this->quyenUpdate = $quyenUpdate;
    }
}