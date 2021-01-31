<?php

namespace App\Services;

use App\Models\Models\User;

class CheckTokenService
{
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
}
