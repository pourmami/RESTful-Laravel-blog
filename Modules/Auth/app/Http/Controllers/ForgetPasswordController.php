<?php

namespace Modules\Auth\app\Http\Controllers;

use App\Models\User;
use Illuminate\Http\JsonResponse;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use Modules\Auth\app\Models\ActivationCode;
use Modules\Auth\app\Http\Requests\ResetPasswordRequest;
use Modules\Auth\app\Http\Requests\SendResetCodeRequest;

class ForgetPasswordController extends Controller
{
    /**
     * @OA\Post(
     *     path="/api/auth/request-reset-password",
     *     summary="ارسال کد بازیابی رمز عبور",
     *     tags={"Auth"},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"email"},
     *             @OA\Property(property="email", type="string", format="email", example="user@example.com")
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="کد بازیابی رمز عبور ارسال شد"
     *     ),
     *     @OA\Response(
     *         response=422,
     *         description="ایمیل نامعتبر یا یافت نشد"
     *     )
     * )
     */
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

        // TODO: Mail::to($request->email)->send(new ForgetPasswordCodeMail($code));

        return response()->json(['message' => 'کد بازیابی رمز عبور ارسال شد']);
    }

    /**
     * @OA\Post(
     *     path="/api/auth/reset-password",
     *     summary="تنظیم رمز جدید با کد تایید",
     *     tags={"Auth"},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"email", "code", "password", "password_confirmation"},
     *             @OA\Property(property="email", type="string", format="email", example="user@example.com"),
     *             @OA\Property(property="code", type="string", example="123456"),
     *             @OA\Property(property="password", type="string", format="password", example="12345678"),
     *             @OA\Property(property="password_confirmation", type="string", format="password", example="12345678")
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="رمز عبور با موفقیت تغییر یافت"
     *     ),
     *     @OA\Response(
     *         response=422,
     *         description="کد تایید نامعتبر یا منقضی شده"
     *     )
     * )
     */
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
