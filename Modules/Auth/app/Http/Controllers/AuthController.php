<?php

namespace Modules\Auth\app\Http\Controllers;

use App\Models\User;
use Illuminate\Http\JsonResponse;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use Modules\Auth\app\Http\Requests\SendActivationCodeRequest;
use Modules\Auth\app\Http\Requests\VerifyActivationCodeRequest;
use Modules\Auth\app\Http\Requests\CompleteRegisterRequest;
use Modules\Auth\Models\ActivationCode;

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

        // TODO: dispatch job to send email

        return response()->json(['message' => 'کد فعال‌سازی ارسال شد.']);
    }

    public function verifyActivationCode(VerifyActivationCodeRequest $request, $id): JsonResponse
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

        return response()->json(['message' => 'کد فعال‌سازی تأیید شد.']);
    }

    public function completeRegister(CompleteRegisterRequest $request): JsonResponse
    {
        $exists = User::where('email', $request->email)->exists();
        if ($exists) {
            return response()->json(['message' => 'کاربر قبلاً ثبت‌نام کرده است.'], 409);
        }

        $lastVerifiedCode = ActivationCode::where('email', $request->email)
            ->where('type', 'register')
            ->where('used', true)
            ->latest()
            ->first();

        if (! $lastVerifiedCode) {
            return response()->json(['message' => 'کدی تأیید نشده است.'], 422);
        }

        $user = User::create([
            'first_name' => $request->first_name,
            'last_name'  => $request->last_name,
            'email'      => $request->email,
            'password'   => Hash::make($request->password),
        ]);

        // assign default role
        $user->assignRole('user');

        return response()->json([
            'message' => 'ثبت‌نام با موفقیت انجام شد.',
            'user' => $user,
        ], 201);
    }
}
