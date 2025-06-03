<?php

namespace Modules\Auth\app\Http\Controllers;

use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Hash;
use App\Http\Controllers\Controller;
use Modules\Auth\app\Models\ActivationCode;
use Modules\Auth\app\Http\Requests\CompleteRegisterRequest;
use Modules\Auth\app\Http\Requests\SendActivationCodeRequest;
use Modules\Auth\app\Http\Requests\VerifyActivationCodeRequest;

class AuthController extends Controller
{
    /**
     * @OA\Post(
     *     path="/api/auth/send-code",
     *     tags={"Auth"},
     *     summary="ارسال کد فعال‌سازی",
     *     description="ایمیل کاربر را گرفته و یک کد فعال‌سازی برای او ارسال می‌کند",
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"email"},
     *             @OA\Property(property="email", type="string", format="email", example="test@example.com")
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="کد فعال‌سازی ارسال شد"
     *     ),
     *     @OA\Response(
     *         response=422,
     *         description="ورودی نامعتبر"
     *     )
     * )
     */
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

    /**
     * @OA\Post(
     *     path="/api/auth/verify-code",
     *     tags={"Auth"},
     *     summary="بررسی صحت کد فعال‌سازی",
     *     description="ایمیل و کد فعال‌سازی را بررسی می‌کند و در صورت معتبر بودن توکن موقت برمی‌گرداند.",
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"email", "code"},
     *             @OA\Property(property="email", type="string", format="email", example="user@example.com"),
     *             @OA\Property(property="code", type="string", example="847395")
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="کد صحیح است، توکن موقت برگردانده شد"
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="کد یا ایمیل نامعتبر است"
     *     ),
     *     @OA\Response(
     *         response=422,
     *         description="خطای اعتبارسنجی"
     *     )
     * )
     */
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
        $token = $user->createToken('pre-register-token', ['pre-register'])->plainTextToken;

        return response()->json([
            'message' => 'کد فعال‌سازی تأیید شد.',
            'token' => $token, // این توکن در هدر Authorization فرستاده میشه
        ]);
    }

    /**
     * @OA\Post(
     *     path="/api/auth/complete-register",
     *     summary="تکمیل ثبت‌نام با توکن موقت",
     *     description="کاربر پس از تأیید ایمیل با کد فعال‌سازی، اطلاعات ثبت‌نام کامل را وارد کرده و ثبت‌نام کامل می‌شود.",
     *     operationId="authCompleteRegister",
     *     tags={"Auth"},
     *     security={{"Bearer":{}}},
     *
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"first_name", "last_name", "password", "password_confirmation"},
     *             @OA\Property(property="first_name", type="string", example="علی"),
     *             @OA\Property(property="last_name", type="string", example="رضایی"),
     *             @OA\Property(property="password", type="string", format="password", example="12345678"),
     *             @OA\Property(property="password_confirmation", type="string", format="password", example="12345678")
     *         )
     *     ),
     *
     *     @OA\Response(
     *         response=200,
     *         description="ثبت‌نام با موفقیت تکمیل شد",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="ثبت‌نام با موفقیت تکمیل شد."),
     *             @OA\Property(property="token", type="string", example="eyJ0eXAiOiJKV1QiLCJhb..."),
     *             @OA\Property(property="user", type="object",
     *                 @OA\Property(property="email", type="string", example="test@example.com"),
     *                 @OA\Property(property="first_name", type="string", example="علی"),
     *                 @OA\Property(property="last_name", type="string", example="رضایی"),
     *             )
     *         )
     *     ),
     *
     *     @OA\Response(
     *         response=403,
     *         description="توکن دسترسی غیرمجاز یا اشتباه"
     *     ),
     *     @OA\Response(
     *         response=422,
     *         description="خطای اعتبارسنجی"
     *     )
     * )
     */
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
            'user' => $user,
        ]);
    }
}
