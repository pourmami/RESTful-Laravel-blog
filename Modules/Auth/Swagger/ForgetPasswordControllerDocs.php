<?php
namespace Modules\Auth\Swagger;
/**
* @OA\Post(
*     path="/api/auth/forgot-password",
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
*
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
class ForgetPasswordControllerDocs {}
