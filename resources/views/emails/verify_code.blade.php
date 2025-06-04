@component('mail::message')
    # کد تایید ثبت‌نام شما

    کد شما: {{ $code }}

    اگر شما درخواست نکردید این ایمیل را نادیده بگیرید.

    با تشکر،
    {{ config('app.name') }}
@endcomponent
