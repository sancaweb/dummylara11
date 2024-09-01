<?php

namespace App\Http\Controllers\Api\Auth;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Helpers\ResponseFormat;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;
use Illuminate\Foundation\Auth\ThrottlesLogins;
use Illuminate\Foundation\Auth\AuthenticatesUsers;

class LoginController extends Controller
{
    /**
     * Handle the incoming request.
     */
    use AuthenticatesUsers, ThrottlesLogins;
    /**
     * @override
     */
    public function username()
    {
        return 'username';
    }

    /**
     * @override
     */

    public function login(Request $request)
    {
        $this->validateLogin($request);

        // If the class is using the ThrottlesLogins trait, we can automatically throttle
        // the login attempts for this application. We'll key this by the username and
        // the IP address of the client making these requests into this application.
        if (
            method_exists($this, 'hasTooManyLoginAttempts') &&
            $this->hasTooManyLoginAttempts($request)
        ) {
            $this->fireLockoutEvent($request);

            return $this->sendLockoutResponse($request);
        }

        // $token = $this->attemptLogin($request);
        $token = auth()->guard('api')->attempt($request->only('username','password'));
        if ($token) {
            //login success
            return ResponseFormat::success([
                "status"=>"Login Successfully",
                "Username"=>$request->username,
                "Password"=>$request->password,
                "token"=>$token
            ],"Login Successfully");
        }

        // If the login attempt was unsuccessful we will increment the number of attempts
        // to login and redirect the user back to the login form. Of course, when this
        // user surpasses their maximum number of attempts they will get locked out.
        $this->incrementLoginAttempts($request);

        return $this->sendFailedLoginResponse($request);
    }


    /**
     * @override
     */
    protected function sendLockoutResponse(Request $request)
    {
        $seconds = $this->limiter()->availableIn(
            $this->throttleKey($request)
        );

        return ResponseFormat::error([
            "second" => $seconds,
            'minutes' => ceil($seconds / 60)

        ], Response::HTTP_TOO_MANY_REQUESTS, 400);
    }


    /**
     * @Override
     */
    protected function sendFailedLoginResponse(Request $request)
    {
        return ResponseFormat::error([
            "error"=>"Login failed for ".$this->username()
        ],"Login failed",401);
    }
}
