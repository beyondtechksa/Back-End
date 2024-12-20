<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Middleware\Authenticate;
use App\Models\User;
use App\Notifications\CustomEmailNotification;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Models\Category;
use App\Models\Brand;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller
{
    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:users',
            'phone' => 'required|string|max:255|unique:users',
            'gender' => 'required|string|max:255|in:male,female',
            'password' => 'required|min:8|confirmed',
        ]);
        if ($validator->fails())
            return response()->json(['errors' => $validator->errors()], 400);
        $data = $request->all();
        $user = User::create([
            'name' => $data['name'],
            'phone' => $data['phone'],
            'email' => $data['email'],
            'gender' => $data['gender'],
            'password' => Hash::make($data['password']),
        ]);
        $data = [
            'phone' => $user->phone,
            'name' => $user->name,
        ];
        return returnSuccess('data', $data, 'user created successfully');
    }
    public function register2Auth(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'uuid' => 'required',
            'phone' => 'nullable|string|max:255',
            'gender' => 'required|string|max:255|in:male,female',
        ]);
        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 400);
        }
        $data = $request->all();
        $user = User::where('email', $request->email)->first();
        if ($user) {
            if ($user->uuid != $request->uuid)
                return response()->json(['errors' => ['uuid' => ['Invalid uuid for your email!']]], 400);
        } else {
            $user = User::create([
                'name' => $data['name'],
                'phone' => $data['phone'] ?? null,
                'email' => $data['email'],
                'gender' => $data['gender'],
                'password' => Hash::make(Str::random(20)),
                'uuid'=>$request->uuid
            ]);
        }
        $token = $user->createToken('AuthToken')->plainTextToken;
        $data = [
            'name' => $user->name,
            'token' => $token
        ];
        return returnSuccess('data', $data, 'login successfully');
    }

    public function login(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|exists:App\Models\User,email',
            'password' => 'required|min:8'
        ]);
        if ($validator->fails())
            return response()->json(['errors' => $validator->errors()], 400);
        if (Auth::attempt($request->only('email', 'password'))) {
            $user = Auth::user();
            Auth::user()->tokens()->delete();
            $request->session()->invalidate();
            $request->session()->regenerateToken();
            $token = $user->createToken('AuthToken')->plainTextToken;
            $data = [
                'name' => $user->name,
                'token' => $token
            ];
            return returnSuccess('data', $data, 'login successfully');
        }
        return response()->json(['error' => 'The provided credentials are incorrect.'], 400);
    }

    public function logout(Request $request)
    {
//        Auth::guard('sanctum')->logout();

        Auth::user()->tokens()->delete();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return returnSuccess('data', '', 'logout successfully');
    }

    public function profile()
    {
        $user = auth()->user();
        $user->avatar = url($user->avatar ?? '/image/default.png');
        return returnSuccess('data', $user, 'success');
    }
    public function updatePassword(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'password' => 'required|min:8|confirmed',
            'old_password' => 'required',
        ]);
        if ($validator->fails())
            return response()->json(['errors' => $validator->errors()], 400);
        $user = User::find(auth()->user()->id);
        if (!Hash::check($request->old_password, $user->password)) {
            return response()->json(['error' => 'Invalid old password'], 401);
        }
        $user->password = Hash::make($request->password);
        $user->save();

        return returnSuccess('data', $user, 'success');
    }
    protected function updateVerifiedUser(User $user, $request): void
    {
        $user->forceFill([
            'name' => $request->first_name . ' ' . $request->last_name,
            'email' => $request->email,
            'email_verified_at' => null,
        ])->save();
        $user->sendEmailVerificationNotification();
    }

    public function forgetPassword(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|exists:App\Models\User,email',
        ]);
        if ($validator->fails())
            return response()->json(['errors' => $validator->errors()], 400);

        $status = Password::sendResetLink(
            $request->only('email')
        );
        return $status === Password::RESET_LINK_SENT
            ? response()->json(['message' => __($status)], 200)
            : response()->json(['message' => __($status)], 400);
    }
    public function resetPassword(Request $request)
    {
        $request->validate([
            'token' => 'required',
            'email' => 'required|email',
            'password' => 'required|min:8|confirmed',
        ]);

        $status = Password::reset(
            $request->only('email', 'password', 'password_confirmation', 'token'),
            function ($user, $password) {
                $user->password = Hash::make($password);
                $user->save();
            }
        );

        return $status === Password::PASSWORD_RESET
            ? response()->json(['message' => __($status)], 200)
            : response()->json(['message' => __($status)], 400);
    }
}
