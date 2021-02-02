<?php

namespace App\Services;

use App\Models\Models\User;

class CheckTokenService
{
    // user
    public function checkTokenUser($request)
    {
        try {
            if (!$request->header('tokenUser')) {
                return [];
            }
            $tokenUser= User::where('role', '=', "user")->where('token', $request->header('tokenUser'))->get();
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
            if (!$request->header('tokenInnkeeper')) {
                return [];
            }
            $tokenUser = User::where('role', '=', "innkeeper")->where('token', $request->header('tokenInnkeeper'))->get();
            if (count($tokenUser) > 0) {
                return $tokenUser;
            } else {
                return [];
            }
        } catch (\Exception $e) {
            return [];
        }
    }
}
