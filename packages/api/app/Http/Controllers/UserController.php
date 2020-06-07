<?php

namespace App\Http\Controllers;

use App\User;
use Validator;
use Illuminate\Validation\Rule;
use Illuminate\Http\Request;
use App\Traits\JWTTrait;
use Symfony\Component\HttpFoundation\Cookie;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    use JWTTrait;

    /**
     * Display a listing of users.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(Request $request)
    {
        $users = User::where('id', '!=', $request->auth->id)->get();
        return response()->json($users);
    }

    /**
     * Display the specified user.
     *
     * @param int $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function show($id)
    {
        $user = User::find($id);
        return response()->json($user);
    }

    /**
     * Update the specified user.
     *
     * @param int $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(Request $request, $id)
    {
        // Validate incoming request
        $this->validate($request, [
            'name' => 'required|string',
            'email' => [
                'required',
                'email',
                Rule::unique('users')->ignore($id),
            ],
            'role' => 'required|string',
        ]);

        $user = User::find($id);

        $user->name = $request->input('name');
        $user->email = $request->input('email');
        $user->role = $request->input('role');

        if (!empty($request->input('password'))) {
            // Validate incoming request
            $this->validate($request, [
                'current_password' => 'required',
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

            if (Hash::check($request->input('current_password'), $user->password)) {
                $plainPassword = $request->input('password');
                $user->password = app('hash')->make($plainPassword);
            } else {
                return response()->json(['current_password' => ['Current password is not valid.']], 422);
            }
        }

        try {
            $user->save();

            return response()->json($user);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Could not update the user'], 400);
        }
    }

    /**
     * Remove the specified user from storage.
     *
     * @param int $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy(Request $request, $id)
    {
        try {
            // Search for the user
            $user = User::find($id);

            // No user found
            if (empty($user)) {
                return response()->json(['message' => 'Could not find user.'], 404);
            }

            $user->delete();

            return response()->json(['message' => 'Deleted user.'], 200);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Could not delete user.'], 400);
        }
    }

    /**
     * Display the authenticated user.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function showSelf(Request $request)
    {
        return response()->json($request->auth);
    }

    /**
     * Update the authenticated user.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function updateSelf(Request $request)
    {
        $updateToken = false;
        $user = $request->auth;

        // Validate incoming request
        $this->validate($request, [
            'name' => 'required|string',
            'email' => [
                'required',
                'email',
                Rule::unique('users')->ignore($user->id),
            ]
        ]);

        // Update token if the username changed
        if ($user->name !== $request->input('name')) {
            $updateToken = true;
        }

        $user->name = $request->input('name');
        $user->email = $request->input('email');

        if (!empty($request->input('password'))) {
            // Validate incoming request
            $this->validate($request, [
                'current_password' => 'required',
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

            if (Hash::check($request->input('current_password'), $user->password)) {
                $plainPassword = $request->input('password');
                $user->password = app('hash')->make($plainPassword);
            } else {
                return response()->json(['current_password' => ['Current password is not valid.']], 422);
            }
        }

        try {
            $user->save();

            // Update token
            if ($updateToken) {
                // Generate new token
                $token = $this->jwt($user);

                return response()
                    ->json($user)
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

            return response()->json($user);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Could not update the user'], 400);
        }
    }
}
