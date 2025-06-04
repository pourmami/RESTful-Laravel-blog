<?php

namespace Modules\Auth\app\Http\Controllers;

use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Http\Controllers\Controller;
use Illuminate\Validation\ValidationException;
use Modules\Auth\app\Http\Requests\LoginRequest;
use Modules\Auth\app\Models\ActivationCode;
use Modules\Auth\app\Http\Requests\CompleteRegisterRequest;
use Modules\Auth\app\Http\Requests\SendActivationCodeRequest;
use Modules\Auth\app\Http\Requests\VerifyActivationCodeRequest;

class AuthController extends Controller
{
    public function sendActivationCode(SendActivationCodeRequest $request): JsonResponse
    {
        $code = rand(100000, 999999);
        ActivationCode::create([
            'email'      => $request->email,
            'code'       => $code,
            'type'       => 'register',
            'expires_at' => now()->addMinutes(10),
        ]);

        // TODO: Mail::to($request->email)->send(new ActivationCodeMail($code));

        return response()->json(['message' => 'کد فعال‌سازی ارسال شد.']);
    }

    public function verifyActivationCode(VerifyActivationCodeRequest $request): JsonResponse
    {
        $code = ActivationCode::where('email', $request->email)
            ->where('code', $request->code)
            ->where('type', 'register')
            ->where('used', false)
            ->where('expires_at', '>', now())
            ->first();

        if (! $code) {
            return response()->json(['message' => 'کد نامعتبر یا منقضی شده است.'], 422);
        }

        $code->used = true;
        $code->save();

        $user = User::create(['email' => $request->email, 'email_verified_at' => now()]);
        $token = $user->createToken('pre-register-token', ['complete-register'])->plainTextToken;

        return response()->json([
            'message' => 'کد فعال‌سازی تأیید شد.',
            'token' => $token, // این توکن در هدر Authorization فرستاده میشه
        ]);
    }

    public function completeRegister(CompleteRegisterRequest $request): JsonResponse
    {
        $user = auth()->user();

        $user->update([
            'first_name' => $request->first_name,
            'last_name'  => $request->last_name,
            'password'   => Hash::make($request->password),
        ]);

        // حذف همه توکن‌های قبلی
        $user->tokens()->delete();

        // assign default role
        $user->assignRole('user');

        // ایجاد توکن دسترسی کامل
        $fullAccessToken = $user->createToken('access-token')->plainTextToken;

        return response()->json([
            'message' => 'ثبت‌نام با موفقیت تکمیل شد.',
            'token' => $fullAccessToken,
            'user' => [
                'email' => $user->email,
                'first_name' => $user->first_name ?? null,
                'last_name' => $user->last_name ?? null,
            ],
        ]);
    }

    public function login(LoginRequest $request): JsonResponse
    {
        if (!Auth::attempt(['email' => $request->email, 'password' => $request->password])) {
            throw ValidationException::withMessages([
                'email' => ['ایمیل یا رمز عبور اشتباه است.'],
            ]);
        }

        $user = Auth::user();
        $fullAccessToken = $user->createToken('access-token')->plainTextToken;

        return response()->json([
            'token' => $fullAccessToken,
            'user' => [
                'email' => $user->email,
                'first_name' => $user->first_name ?? null,
                'last_name' => $user->last_name ?? null,
            ],
        ]);
    }
}
