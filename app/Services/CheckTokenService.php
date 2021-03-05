<?php

namespace App\Services;

use App\Models\Models\User;
use App\Models\Models\Quan;

class CheckTokenService
{
    public function getTokenByPhone($request, $role)
    {
        $user = User::where('role', '=', $role)->where('phone', $request->get('phone'))->get();
        if (count($user)> 0) {
            return $user[0]->token;
        } else {
            return true;
        }
         
    }
    
    public function checkTokenUser($request)
    {
        try {
            $token=$request->header('tokenUser');
            if (!$token) {
                return [];
            }
            $tokenUser= User::where('role', '=', "user")->where('token', $token)->get();
            if (count($tokenUser) > 0) {
                return $tokenUser;
            } else {
                return [];
            }
        } catch (\Exception $e) {
            return [];
        }
    }

    public function checkTokenAdmin($request)
    {
        try {
            if (!$request->header('tokenAdmin')) {
                return [];
            }
             
            $tokenUser = User::where('role', '=', "admin")->where('token', $request->header('tokenAdmin'))->get();
            
            if (count($tokenUser) > 0) {
                return $tokenUser;
            } else {
                return [];
            }
        } catch (\Exception $e) {
            return [];
        }
    }


    public function checkTokenInnkeeper($request)
    {
        try {
            $token = $request->header('tokenInnkeeper');
            if (!$token) {
                return [];
            }
            $user = User::where('role', '=', "innkeeper")->where('token', $token)->get();
            if (count($user) > 0) {
                return $user;
            } else {
                return [];
            }
        } catch (\Exception $e) {
            return [];
        }
    }

    public function checkTokenInnkeeperAndIdquan($request)
    {
        try {
            $token = $this->checkTokenInnkeeper($request);
            if(count($token) > 0) {
                $quan=Quan::where("id",$request->get('idquan'))->get();
                if (count($quan)> 0) {
                    if ($token[0]->phone==$quan[0]->phone) {
                        return $token;
                    }
                }                
            }
            return [];
        } catch (\Exception $e) {
            return [];
        }
    }
}
