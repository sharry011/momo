@extends('layouts.main_layout')

@section('content')
    <div style="margin-block: 10vh;">
        <div class="text-white">
            <h1 style="text-align: center;">خطأ</h1>
            <h1 style="text-align: center;">! 4 <span style="color: var(--color-main)">0</span> 4 !</h1>
            <p style="text-align: center;">
                <span class="fs-4"><b> الصفحه التي طلبتها غير موجوده </b></span>
            </p>
            <p style="text-align: center;">
                <span class="fs-4"><b> ربما قد تم حذف الصفحه أو إنها غير صالحه بعد الأن </b></span>
            </p>
            <p style="text-align: center;">
                <span class="fs-4"><b>يرجى إستخدام خاصية البحث للتأكد</b></span>
            </p>
            <p style="text-align: center;">
                <span class="fs-4"><b><br></b></span>
            </p>
            <div style="text-align: center;">
                <span class="fs-4"><b>شكراً لكم .. إدارة موقع {{$settings->site_name}}</b></span>
            </div>
            <span class="fs-4" color="#00ccff">
                <b style="">
                    <div style="text-align: center; color: var(--color-main); font-weight: bolder; line-height: 2;">
                        <a href="{{url('/')}}" style="color: var(--color-main);">{{$settings->site_name}}</a>
                    </div>
                </b>
            </span>
        </div>
    </div>
@endsection
