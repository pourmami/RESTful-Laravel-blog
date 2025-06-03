<?php

namespace Modules\Auth\app\Http\Controllers;

use App\Models\User;
use Illuminate\Http\JsonResponse;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use Modules\Auth\app\Http\Requests\SendActivationCodeRequest;
use Modules\Auth\app\Http\Requests\VerifyActivationCodeRequest;
use Modules\Auth\app\Http\Requests\CompleteRegisterRequest;
use Modules\Auth\app\Models\ActivationCode;

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

        // TODO: dispatch job to send email

        return response()->json(['message' => 'کد فعال‌سازی ارسال شد.']);
    }

    /**
     * @OA\Post(
     *     path="/api/auth/check-code",
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

    /**
     * @OA\Post(
     *     path="/api/auth/complete-register",
     *     tags={"Auth"},
     *     summary="تکمیل ثبت‌نام کاربر پس از تأیید ایمیل",
     *     description="با دریافت نام، نام‌خانوادگی و رمز عبور، ثبت‌نام را نهایی می‌کند. توکن موقت باید همراه درخواست ارسال شود.",
     *     security={{"Bearer":{}}},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"first_name", "last_name", "password"},
     *             @OA\Property(property="first_name", type="string", example="محمد"),
     *             @OA\Property(property="last_name", type="string", example="جعفری"),
     *             @OA\Property(property="password", type="string", format="password", example="StrongPassword123!")
     *         )
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="ثبت‌نام با موفقیت انجام شد و توکن نهایی بازگردانده شد"
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="توکن موقت نامعتبر یا منقضی شده"
     *     ),
     *     @OA\Response(
     *         response=422,
     *         description="خطای اعتبارسنجی"
     *     )
     * )
     */
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
