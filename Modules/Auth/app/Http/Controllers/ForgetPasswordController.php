<?php

namespace Modules\Auth\app\Http\Controllers;

use App\Models\User;
use App\Mail\ResetPasswordEmail;
use Illuminate\Http\JsonResponse;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Modules\Auth\app\Models\ActivationCode;
use Modules\Auth\app\Http\Requests\ResetPasswordRequest;
use Modules\Auth\app\Http\Requests\SendResetCodeRequest;

class ForgetPasswordController extends Controller
{
    public function requestReset(SendResetCodeRequest $request): JsonResponse
    {
        $code = rand(100000, 999999);

        ActivationCode::updateOrInsert(
            ['email' => $request->email, 'type' => 'reset'],
            [
                'code' => $code,
                'expires_at' => now()->addMinutes(15),
            ]
        );

        Mail::to($request->email)->queue(new ResetPasswordEmail());

        return response()->json(['message' => 'کد بازیابی رمز عبور ارسال شد']);
    }

    public function resetPassword(ResetPasswordRequest $request): JsonResponse
    {
        $record = ActivationCode::where('email', $request->email)
            ->where('code', $request->code)
            ->where('type', 'reset')
            ->where('expires_at', '>=', now())
            ->first();

        if (!$record) {
            return response()->json(['message' => 'کد وارد شده نامعتبر یا منقضی شده است.'], 422);
        }

        $user = User::where('email', $request->email)->first();
        $user->update(['password' => Hash::make($request->password)]);

        ActivationCode::where('email', $request->email)
            ->where('type', 'reset')
            ->delete();

        return response()->json(['message' => 'رمز عبور با موفقیت تغییر یافت']);
    }
}
