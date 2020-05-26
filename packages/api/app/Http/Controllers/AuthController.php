<?php

namespace App\Http\Controllers;

use Validator;
use App\User;
use App\Traits\JWTTrait;
use Firebase\JWT\JWT;
use Illuminate\Http\Request;
use Firebase\JWT\ExpiredException;
use Illuminate\Support\Facades\Hash;
use Symfony\Component\HttpFoundation\Cookie;
use App\Exceptions\Handler;
use Laravel\Lumen\Routing\Controller as BaseController;

class AuthController extends BaseController
{
    use JWTTrait;

    /**
     * The request instance.
     *
     * @var \Illuminate\Http\Request
     */
    private $request;

    /**
     * Create a new controller instance.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return void
     */
    public function __construct(Request $request)
    {
        $this->request = $request;
    }

    /**
     * Authenticate a user and return the token if the provided credentials are correct.
     *
     * @param  \App\User   $user
     * @return mixed
     */
    public function authenticate(User $user)
    {
        $this->validate($this->request, [
            'email'     => 'required|email',
            'password'  => 'required'
        ]);

        // Find the user by email
        $user = User::where('email', $this->request->input('email'))->first();

        if (!$user) {
            // You wil probably have some sort of helpers or whatever
            // to make sure that you have the same response format for
            // differents kind of responses. But let's return the
            // below respose for now.
            return response()->json([
                'error' => 'Email does not exist.'
            ], 400);
        }

        // Verify the password and generate the token
        if (Hash::check($this->request->input('password'), $user->password)) {
            $token = $this->jwt($user);

            return response()
                ->json($user, 200)
                // Set a http cookie with the token as value
                ->withCookie(new Cookie(
                    'jwt',
                    $token,
                    (time() + 60 * 60),
                    '/',
                    env('COOKIE_DOMAIN', 'localhost'),
                    false,
                    true
                ));
        }

        // Bad Request response
        return response()->json([
            'error' => 'Email or password is wrong.'
        ], 400);
    }

    /**
     * Store a new user.
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function register(Request $request)
    {
        // Validate incoming request
        $this->validate($request, [
            'name' => 'required|string',
            'email' => 'required|email|unique:users',
            'password' => [
                'required',
                'confirmed',            // Must be confirmed with password_confirmed
                'min:8',                // Must be at least 8 characters in length
                'regex:/[a-z]/',        // Must contain at least one lowercase letter
                'regex:/[A-Z]/',        // Must contain at least one uppercase letter
                'regex:/[0-9]/',        // Must contain at least one digit
                'regex:/[@$!%*#?&]/',   // Must contain a special character
            ]
        ]);

        try {
            $user = new User;
            $user->name = $request->input('name');
            $user->email = $request->input('email');
            $plainPassword = $request->input('password');
            $user->password = app('hash')->make($plainPassword);

            $user->save();

            // Return successful response
            return response()->json(['user' => $user, 'message' => 'User registration succeeded'], 201);
        } catch (\Exception $e) {
            // Return error message
            return response()->json(['message' => 'User registration failed!'], 409);
        }
    }

    /**
     * Log out a user.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function logout()
    {
        // Let the token expire
        setcookie('jwt', '', -1, '/', 'localhost', false, true);
        return response()
            ->json(['message' => 'User has logged out.'])
            ->withCookie(new Cookie('jwt', '', -1, '/', env('COOKIE_DOMAIN', 'localhost'), false, true));
    }
}
