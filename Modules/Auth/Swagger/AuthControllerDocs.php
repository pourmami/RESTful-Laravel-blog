<?php
namespace Modules\Auth\Swagger;
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
*
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
*
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
*
* @OA\Post(
*     path="/api/auth/login",
*     summary="ورود کاربر با ایمیل و رمز عبور",
*     tags={"Auth"},
*     @OA\RequestBody(
*         required=true,
*         @OA\JsonContent(
*             required={"email","password"},
*             @OA\Property(property="email", type="string", format="email", example="user@example.com"),
*             @OA\Property(property="password", type="string", format="password", example="12345678")
*         )
*     ),
*     @OA\Response(
*         response=200,
*         description="ورود موفق",
*         @OA\JsonContent(
*             @OA\Property(property="token", type="string", example="1|abc123def456"),
*             @OA\Property(
*                 property="user",
*                 type="object",
*                 @OA\Property(property="id", type="integer", example=1),
*                 @OA\Property(property="email", type="string", example="user@example.com"),
*                 @OA\Property(property="first_name", type="string", example="علی"),
*                 @OA\Property(property="last_name", type="string", example="رضایی")
*             )
*         )
*     ),
*     @OA\Response(
*         response=422,
*         description="خطا در اعتبارسنجی یا اطلاعات ورود نامعتبر",
*         @OA\JsonContent(
*             @OA\Property(property="message", type="string", example="The given data was invalid."),
*             @OA\Property(
*                 property="errors",
*                 type="object",
*                 @OA\Property(
*                     property="email",
*                     type="array",
*                     @OA\Items(type="string", example="ایمیل یا رمز عبور اشتباه است.")
*                 )
*             )
*         )
*     )
* )
*/
class AuthControllerDocs {}
