<?php

namespace Modules\Auth\app\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Modules\Auth\Http\Requests\SendActivationCodeRequest;
use Modules\Auth\Http\Requests\VerifyActivationCodeRequest;
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

        if (!$code) {
            return response()->json(['message' => 'کد نامعتبر یا منقضی شده است.'], 422);
        }

        $code->used = true;
        $code->save();

        return response()->json(['message' => 'کد فعال‌سازی تأیید شد.']);
    }
}
