<?php

namespace Modules\Article\Swagger;
/**
 * @OA\Schema(
 *      schema="ArticleResource",
 *      type="object",
 *      title="Article Resource",
 *      @OA\Property(property="id", type="integer", example=1),
 *      @OA\Property(property="title", type="string", example="عنوان مقاله"),
 *      @OA\Property(property="slug", type="string", example="article-title"),
 *      @OA\Property(property="body", type="string", example="محتوای کامل مقاله"),
 *      @OA\Property(property="published_at", type="string", format="date-time", example="2025-06-04T15:00:00Z"),
 *      @OA\Property(property="category", type="object",
 *          @OA\Property(property="id", type="integer", example=2),
 *          @OA\Property(property="name", type="string", example="الکترونیک")
 *      ),
 *      @OA\Property(property="author", type="object",
 *          @OA\Property(property="id", type="integer", example=5),
 *          @OA\Property(property="name", type="string", example="Mohammad")
 *      )
 *  )
 *  @OA\Schema(
 *     schema="StoreArticleRequest",
 *     type="object",
 *     required={"title", "slug", "body", "status"},
 *     @OA\Property(property="title", type="string", example="عنوان مقاله"),
 *     @OA\Property(property="slug", type="string", example="article-title"),
 *     @OA\Property(property="body", type="string", example="متن کامل مقاله..."),
 *     @OA\Property(property="excerpt", type="string", example="خلاصه‌ای از مقاله..."),
 *     @OA\Property(property="status", type="string", enum={"published", "draft", "scheduled"}, example="draft"),
 *     @OA\Property(property="published_at", type="string", format="date-time", example="2025-06-04T10:00:00Z"),
 *     @OA\Property(property="archived_at", type="string", format="date-time", example="2025-06-10T10:00:00Z"),
 *     @OA\Property(property="category_id", type="integer", example=1)
 *  )
 *
 * @OA\Schema(
 *      schema="UpdateArticleRequest",
 *      type="object",
 *      required={"title", "slug", "body", "category_id", "published_at"},
 *      @OA\Property(property="title", type="string", example="عنوان جدید مقاله"),
 *      @OA\Property(property="slug", type="string", example="new-article-title"),
 *      @OA\Property(property="body", type="string", example="محتوای جدید مقاله"),
 *      @OA\Property(property="category_id", type="integer", example=3),
 *      @OA\Property(property="published_at", type="string", format="date-time", example="2025-06-05T10:00:00Z")
 *  )
 *
 * @OA\Get(
 *     path="/api/articles",
 *     summary="لیست مقالات با قابلیت فیلتر و صفحه‌بندی",
 *     tags={"Articles"},
 *     @OA\Parameter(
 *          name="author_name",
 *          in="query",
 *          description="فیلتر بر اساس نام نویسنده مقاله",
 *          required=false,
 *          @OA\Schema(type="string")
 *      ),
 *      @OA\Parameter(
 *          name="category_name",
 *          in="query",
 *          description="فیلتر بر اساس نام دسته‌بندی",
 *          required=false,
 *          @OA\Schema(type="string")
 *      ),
 *      @OA\Parameter(
 *          name="search",
 *          in="query",
 *          description="جستجو در عنوان یا متن مقاله",
 *          required=false,
 *          @OA\Schema(type="string")
 *      ),
 *     @OA\Response(
 *         response=200,
 *         description="لیست مقالات با موفقیت بازگردانده شد",
 *         @OA\JsonContent(type="array", @OA\Items(ref="#/components/schemas/ArticleResource"))
 *     )
 * )
 *
 * @OA\Post(
 *      path="/api/articles",
 *      summary="ایجاد مقاله جدید",
 *      tags={"Articles"},
 *      security={{"Bearer":{}}},
 *      @OA\RequestBody(
 *          required=true,
 *          @OA\JsonContent(ref="#/components/schemas/StoreArticleRequest")
 *      ),
 *      @OA\Response(
 *          response=201,
 *          description="مقاله با موفقیت ایجاد شد",
 *          @OA\JsonContent(ref="#/components/schemas/ArticleResource")
 *      )
 *  )
 *
 * @OA\Put(
 *      path="/api/articles/{id}",
 *      summary="بروزرسانی مقاله",
 *      tags={"Articles"},
 *      security={{"Bearer":{}}},
 *      @OA\Parameter(
 *          name="id",
 *          in="path",
 *          required=true,
 *          @OA\Schema(type="integer")
 *      ),
 *      @OA\RequestBody(
 *          required=true,
 *          @OA\JsonContent(ref="#/components/schemas/UpdateArticleRequest")
 *      ),
 *      @OA\Response(
 *          response=200,
 *          description="مقاله با موفقیت بروزرسانی شد",
 *          @OA\JsonContent(ref="#/components/schemas/ArticleResource")
 *      )
 *  )
 *
 * @OA\Delete(
 *      path="/api/articles/{id}",
 *      summary="حذف مقاله",
 *      tags={"Articles"},
 *      security={{"Bearer":{}}},
 *      @OA\Parameter(
 *          name="id",
 *          in="path",
 *          required=true,
 *          @OA\Schema(type="integer")
 *      ),
 *      @OA\Response(
 *          response=200,
 *          description="مقاله حذف شد",
 *          @OA\JsonContent(
 *              @OA\Property(property="message", type="string", example="مقاله حذف شد.")
 *          )
 *      )
 *  )
 */
class ArticleControllerDocs {}
