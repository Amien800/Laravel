<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Mail\RegisterMail;
use App\Models\OtpCode;
use App\Models\Roles;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;
use Tymon\JWTAuth\Facades\JWTAuth;

class AutheController extends Controller
{
    public function index()
    {
        $user = User::all();

        return response()->json(
            [
                'success' => true,
                'message' => 'Berhasil Menampilkan Data User',
                'data' => $user,
            ],
            200
        );
    }

    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
        ]);

        //if validation fails
        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        $roleUser = Roles::where('name', 'user')->first();

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role_id' => $roleUser->id,
        ]);

        $user->generateOtpCode();
        $token = JWTAuth::fromUser($user);

        Mail::to($user->email)->queue(new RegisterMail($user));

        if (!$user) {
            return response()->json(
                [
                    'message' => 'Gagal Registrasi User',
                ],
                409
            );
        }

        return response()->json(
            [
                'message' =>
                    'Berhasil Registrasi User dan Kode OTP Sudah Terkirim',
                'user' => $user,
                'token' => $token,
            ],
            201
        );
    }

    public function me()
    {
        $user = auth()->user();
        $currentUser = User::with(
            'Role',
            'profile',
            'historyBorrow',
            'loadBorrow'
        )->find($user->id);
        return response()->json([
            'message' => 'Berhasil Mendapatkan Data User',
            'user' => $currentUser,
        ]);
    }

    public function login(Request $request)
    {
        $credentials = request(['email', 'password']);
        if (!($user = auth()->attempt($credentials))) {
            return response()->json(
                [
                    'message' => 'User Unauthorized',
                ],
                401
            );
        }

        $UserData = User::with('Role', 'profile', 'historyBorrow', 'loadBorrow')
            ->where('email', $request['email'])
            ->first();
        $token = JWTAuth::fromUser($UserData);
        return response()->json([
            'message' => 'Berhasil Login',
            'user' => $UserData,
            'token' => $token,
        ]);
    }

    public function logout()
    {
        auth()->logout();

        return response()->json(['message' => 'Berhasil logged out']);
    }

    public function otpCode(Request $request)
    {
        // $request->validate([
        //     'email' => 'required|email',
        // ]);
        $currentUser = auth()->user();
        $userData = User::find($currentUser->id);
        // $userData = User::where('email', $request->email)->first();

        $userData->generateOtpCode();

        Mail::to($userData->email)->queue(new RegisterMail($userData));

        return response()->json(
            [
                'success' => true,
                'message' => 'Berhasil Mengenerate Ulang Kode OTP',
                'data' => $userData,
            ],
            201
        );
    }

    public function validation(Request $request)
    {
        $request->validate([
            'otp' => 'required',
        ]);

        //mengecheck kode otp ada di database
        $otp_code = OtpCode::where('otp', $request->otp)->first();

        if (!$otp_code) {
            return response()->json(
                [
                    'message' => 'Kode OTP tidak ditemukan',
                ],
                404
            );
        }

        $now = Carbon::now()->format('Y-m-d H:i:s');
        // check kode otp sudah kadaluarsa atau belum
        if ($now > $otp_code->valid_until) {
            return response()->json(
                [
                    'message' => 'Kode OTP Kadaluarsa',
                ],
                400
            );
        }

        //update user email verficated
        $user = User::find($otp_code->user_id);
        $user->email_verified_at = $now;

        $user->save();

        $otp_code->delete();

        return response()->json(
            [
                'message' => 'Email Berhasil di verifikasi',
            ],
            200
        );
    }
}
