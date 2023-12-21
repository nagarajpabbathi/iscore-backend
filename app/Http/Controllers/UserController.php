<?php

namespace App\Http\Controllers;

use App\Http\Requests\{
    ForgetPasswordRequest,
    ForgetPasswordUpdateRequest,
    LoginRequest,
    ProfileRequest,
    RegisterRequest,
};

use App\Mail\ForgetPasswordMail;
use App\Models\User;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class UserController extends Controller
{
    public function index(Request $request)
    {
        $users = User::when(isset($request->name), function ($q) use ($request) {
            $q->where('name', 'like', "%{$request->name}%");
        })
            ->when(isset($request->mobile), function ($q) use ($request) {
                $q->where('mobile', 'like', "%{$request->mobile}%");
            })
            ->when(isset($request->status), function ($q) use ($request) {
                $q->where('status', $request->status);
            })
            ->when(isset($request->email), function ($q) use ($request) {
                $q->where('email', 'like', "%{$request->email}%");
                $q->Orwhere('username', 'like', "%{$request->email}%");
            })
            ->orderBy('id', 'desc')
            ->paginate(10);

        return view('user.list', compact('users'));
    }

    public function register(RegisterRequest $request)
    {
        $input = $request->validated();

        $input['password'] = bcrypt($input['password']);

        $res = User::create($input);

        return response()
            ->json([
                'message' => 'User successfully register!',
                'data' => $res,
                'response' => true
            ], 200);
    }

    public function login(LoginRequest $request)
    {
        $input = $request->validated();

        $username = filter_var($input['username'], FILTER_SANITIZE_EMAIL);

        $key = !filter_var($username, FILTER_VALIDATE_EMAIL) === false ? 'email' : 'username';

        if (auth()->attempt([$key => $input['username'], 'password' => $input['password']])) {

            $user = auth()->user();

            $user->token = $user->createToken('MyApp')->plainTextToken;

            return response()
                ->json([
                    'message' => 'User successfully login!',
                    'data' => $user,
                    'response' => true
                ], 200);
        }
        return response()
            ->json([
                'message' => 'Username or password is wrong!',
                'data' => [],
                'response' => false
            ], 200);
    }

    public function userDetails()
    {
        $user  = auth()->user();

        return response()
            ->json([
                'message' => 'User details!',
                'data' => $user,
                'response' => true
            ], 200);
    }

    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();

        return response()
            ->json([
                'message' => 'User logout successfully!',
                'data' => [],
                'response' => true
            ], 200);
    }

    public function userProfileUpdate(ProfileRequest $request)
    {
        $input = $request->validated();

        $user = auth()->user();

        if (isset($input['password'])) {

            $user->password = bcrypt($input['password']);
        }

        if (isset($input['profile_image'])) {

            $user->profile_image = Storage::disk('public')
                ->put('user_profile_image', $input['profile_image']);

            if (Storage::disk('public')->exists($user->profile_image)) {

                Storage::disk('public')->delete($user->profile_image);
            }
        }

        $user->name = $input['name'];
        $user->email = $input['email'];
        $user->username = $input['username'];
        $user->mobile = $input['mobile'];

        $user->save();

        return response()
            ->json([
                'message' => 'User profile updated successfully!',
                'data' => auth()->user(),
                'response' => true
            ], 200);
    }

    public function forgetPassword(ForgetPasswordRequest $request)
    {
        $input = $request->validated();

        $username = filter_var($input['username'], FILTER_SANITIZE_EMAIL);

        $key = !filter_var($username, FILTER_VALIDATE_EMAIL) === false ? 'email' : 'username';

        $token = Str::random(100);

        $user = User::where($key, $input['username'])->first();

        $mail = [];

        $mail['email'] = $user->email;
        $mail['token'] = $token;

        $user->update(['remember_token' => $token]);

        Mail::to($user->email)->send(new ForgetPasswordMail($mail));

        return response()
            ->json([
                'message' => 'Password successfully send on email address!',
                'data' => [],
                'response' => true
            ], 200);
    }

    public function passwordUpdate(ForgetPasswordUpdateRequest $request)
    {
        $input = $request->validated();

        $user = User::where('remember_token', $input['token'])
            ->where('email', $input['email'])
            ->where('updated_at', '>=', now()->addMinutes(-5))
            ->first();

        if ($user) {

            $user->update(['password' => bcrypt($input['password']), 'remember_token' => null]);

            return response()
                ->json([
                    'message' => 'Password successfully updated!',
                    'data' => [],
                    'response' => true
                ], 200);
        }

        return response()
            ->json([
                'message' => 'Token not match with the given email address or token expire',
                'response' => false
            ], 422);
    }
}
