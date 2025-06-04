<?php

namespace Modules\Category\Swagger;
/**
 * @OA\Get(
 *     path="/api/categories",
 *     summary="لیست دسته‌بندی‌ها",
 *     tags={"Category"},
 *     @OA\Response(
 *         response=200,
 *         description="لیست دسته‌بندی‌ها با زیردسته‌ها"
 *     )
 * )
 *
 * @OA\Get(
 *     path="/api/categories/{id}",
 *     summary="دریافت جزئیات دسته‌بندی",
 *     tags={"Category"},
 *     @OA\Parameter(
 *         name="id",
 *         in="path",
 *         required=true,
 *         description="شناسه دسته‌بندی",
 *         @OA\Schema(type="integer")
 *     ),
 *     @OA\Response(
 *         response=200,
 *         description="جزئیات دسته‌بندی با زیردسته‌ها"
 *     ),
 *     @OA\Response(
 *         response=404,
 *         description="دسته‌بندی پیدا نشد"
 *     )
 * )
 *
 * @OA\Post(
 *     path="/api/categories",
 *     summary="ایجاد دسته‌بندی جدید",
 *     tags={"Category"},
 *     security={{"Bearer":{}}},
 *     @OA\RequestBody(
 *         required=true,
 *         @OA\JsonContent(
 *             required={"name", "slug"},
 *             @OA\Property(property="name", type="string", example="الکترونیک"),
 *             @OA\Property(property="slug", type="string", example="electronics"),
 *             @OA\Property(property="parent_id", type="integer", nullable=true, example=1)
 *         )
 *     ),
 *     @OA\Response(
 *         response=201,
 *         description="دسته‌بندی با موفقیت ایجاد شد"
 *     ),
 *     @OA\Response(
 *         response=422,
 *         description="خطای اعتبارسنجی"
 *     )
 * )
 *
 * @OA\Put(
 *     path="/api/categories/{id}",
 *     summary="بروزرسانی دسته‌بندی",
 *     tags={"Category"},
 *     security={{"Bearer":{}}},
 *     @OA\Parameter(
 *         name="id",
 *         in="path",
 *         required=true,
 *         description="شناسه دسته‌بندی",
 *         @OA\Schema(type="integer")
 *     ),
 *     @OA\RequestBody(
 *         required=true,
 *         @OA\JsonContent(
 *             @OA\Property(property="name", type="string", example="الکترونیک به‌روز شده"),
 *             @OA\Property(property="slug", type="string", example="updated-electronics"),
 *             @OA\Property(property="parent_id", type="integer", nullable=true, example=null)
 *         )
 *     ),
 *     @OA\Response(
 *         response=200,
 *         description="دسته‌بندی بروزرسانی شد"
 *     ),
 *     @OA\Response(
 *         response=404,
 *         description="دسته‌بندی پیدا نشد"
 *     ),
 *     @OA\Response(
 *         response=422,
 *         description="خطای اعتبارسنجی"
 *     )
 * )
 *
 * @OA\Delete(
 *     path="/api/categories/{id}",
 *     summary="حذف دسته‌بندی",
 *     tags={"Category"},
 *     security={{"Bearer":{}}},
 *     @OA\Parameter(
 *         name="id",
 *         in="path",
 *         required=true,
 *         description="شناسه دسته‌بندی",
 *         @OA\Schema(type="integer")
 *     ),
 *     @OA\Response(
 *         response=204,
 *         description="دسته‌بندی با موفقیت حذف شد"
 *     ),
 *     @OA\Response(
 *         response=404,
 *         description="دسته‌بندی پیدا نشد"
 *     )
 * )
 */
class CategoryControllerDocs
{}
