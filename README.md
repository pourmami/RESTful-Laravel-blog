# 📰 Laravel RESTful Blog API

یک پروژه‌ی بک‌اند بلاگ حرفه‌ای با معماری ماژولار و مبتنی بر Laravel برای مصاحبه یا استفاده در پروژه‌های واقعی. این API شامل سیستم احراز هویت مبتنی بر کد تائید، مدیریت نقش‌ها، دسته‌بندی‌ها و مقالات زمان‌بندی‌شده است.

---

## ⚙️ Tech Stack

- Laravel 10+
- PHP 8.2+
- MySQL
- JWT Auth
- Swagger (L5-Swagger)
- Modular Architecture (`nwidart/laravel-modules`)
- Laravel Queues + Mail
- PHPUnit

---

## 📦 Features

- ✅ ثبت‌نام و ورود با کد تائید ایمیل
- ✅ فراموشی رمز عبور با ارسال ایمیل
- ✅ مدیریت نقش‌ها و مجوزها (Policies)
- ✅ CRUD کامل دسته‌بندی‌ها (تودرتو)
- ✅ CRUD کامل مقالات
- ✅ فیلتر، جستجو و صفحه‌بندی مقالات
- ✅ زمان‌بندی انتشار مقالات (`published_at`)
- ✅ سیستم صف برای ایمیل‌ها
- ✅ Swagger برای مستندسازی API
- ✅ کش کردن لیست مقالات و دسته‌ها
- ✅ تست‌نویسی با PHPUnit

---

## 🚀 نصب و راه‌اندازی 

## پیش‌نیازها 

اطمینان حاصل کنید که ابزارهای زیر روی سیستم شما نصب شده باشند:

- PHP 8.1 یا بالاتر
- Composer
- MySQL یا MariaDB
- Node.js و NPM (در صورت نیاز به frontend)
- Git

## 1. کلون کردن پروژه 

```bash
git clone https://github.com/your-username/blog-api.git
cd blog-api
```

##  2. PHP نصب پکیج‌های
```bash
composer install
```

## 3. ایجاد فایل پیکربندی .env
```bash
cp .env.example .env
php artisan key:generate
```

## 4. تنظیمات اتصال به دیتابیس 
در فایل .env مقادیر زیر را با اطلاعات سیستم خود جایگزین کنید:
```bash
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=blog_db
DB_USERNAME=root
DB_PASSWORD=
```
سپس دستورات زیر را برای اجرای مایگریشن‌ها و دیتای اولیه اجرا کنید:
```bash
php artisan migrate --seed
```

## تنظیمات ایمیل 
```bash
MAIL_MAILER=smtp
MAIL_HOST=smtp.gmail.com
MAIL_PORT=587
MAIL_USERNAME=your_email@gmail.com
MAIL_PASSWORD=your_app_password
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS=your_email@gmail.com
MAIL_FROM_NAME="Blog API"
```
توجه: اگر از Gmail استفاده می‌کنید، باید یک [App Password](https://support.google.com/accounts/answer/185833?hl=fa) بسازید. رمز عبور حساب کاربری اصلی پشتیبانی نمی‌شود.

## فعال‌سازی صف‌ها 
برای اجرای وظایف صف‌بندی‌شده مانند ارسال ایمیل:
 
تنظیمات .env :
```bash
QUEUE_CONNECTION=database
```

1. ساخت جدول صف :
```bash
php artisan queue:table
php artisan migrate
```
2. اجرای صف :
```bash
php artisan queue:work
```
## مستندات API (Swagger) 
برای تولید و مشاهده مستندات API:

```bash
php artisan l5-swagger:generate
```
سپس در مرورگر باز کنید:
```bash
http://localhost:8000/api/documentation
```
