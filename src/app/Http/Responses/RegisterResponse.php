<?php

namespace App\Http\Responses;

use Laravel\Fortify\Contracts\RegisterResponse as RegisterResponseContract;

class RegisterResponse implements RegisterResponseContract
{
    public function toResponse($request)
    {
        return redirect('/attendance');

        /* あとでメール認証
         * return redirect()->route('');
         */
    }
}