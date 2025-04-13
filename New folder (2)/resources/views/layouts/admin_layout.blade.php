<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>@yield('title')</title>

    <link rel="shortcut icon" type="image/png" href="{{url('/faveicon.ico?v=' . time())}}"/>

    <meta name="viewport" content="width=device-width, initial-scale=1">

    <link rel="stylesheet" href="{{url('/assets/select2/base.css')}}">
    <link rel="stylesheet" href="{{url('/assets/nestable/nestable.css')}}">
    <link rel="stylesheet" media="screen" href="{{url('/bootstrap/css/bootstrap.min.css')}}">
    <link rel="stylesheet" href="{{url('/assets/main_style.css')}}">
    <link rel="stylesheet" href="{{url('/assets/admin_style.css')}}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <link href="https://fonts.googleapis.com/css2?family=Tajawal:wght@200;300;400;500;700;800;900&display=swap"
          rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">

    <script src="{{url('/assets/jquery.js')}}"></script>
    <script src="{{url('/bootstrap/js/bootstrap.bundle.min.js')}}"></script>
    <script src="{{url('/assets/select2/base.js')}}"></script>
    <script src="{{url('/assets/nestable/nestable.js')}}"></script>
    <script src="{{url('/assets/swal/swal.js')}}"></script>

    @yield('head')
</head>

<body>

<div id="admin_preloader" class='simplePreloader loaded'>
    <div class="preloader_wrapper">
        <span class="loader"></span>
    </div>
</div>

<div class="wrapper">
    <div class="sidebar">
        <div class="logo">
            Admin Panel
        </div>
        <div class="items" id="items">

            <div class="accordion-item">
                <h2 class="accordion-header" id="heading1">
                    <button class="sidebar_menu_btn" data-bs-toggle="collapse" data-bs-target="#shows"
                            aria-expanded="true" aria-controls="shows" type="button">
                        <i class="fa-solid fa-video"></i>
                        <span>المقالات</span>
                    </button>
                </h2>
                <div id="shows" class="accordion-collapse collapse " aria-labelledby="heading1"
                     data-bs-parent="#items">
                    <div class="accordion-body">
                        <div class="sidebar_items">
                            <a href="{{route('admin.articles.all.get')}}" class="item">كل المقالات</a>
                            <a href="{{route('admin.articles.new.get')}}" class="item">اضافة مقال</a>
                        </div>
                    </div>
                </div>
            </div>

            <div class="accordion-item">
                <h2 class="accordion-header" id="heading2">
                    <button class="sidebar_menu_btn" data-bs-toggle="collapse" data-bs-target="#seasons"
                            aria-expanded="true" aria-controls="shows" type="button">
                        <i class="fa-solid fa-layer-group"></i>
                        <span>المواسم</span>
                    </button>
                </h2>
                <div id="seasons" class="accordion-collapse collapse " aria-labelledby="heading2"
                     data-bs-parent="#items">
                    <div class="accordion-body">
                        <div class="sidebar_items">
                            <a href="{{route('admin.seasons.all.get')}}" class="item">كل المواسم</a>
                            <a href="{{route('admin.seasons.new.get')}}" class="item">اضافة موسم</a>
                        </div>
                    </div>
                </div>
            </div>
            <hr class="m-0 p-0"/>

            <div class="accordion-item">
                <h2 class="accordion-header" id="heading3">
                    <button class="sidebar_menu_btn" data-bs-toggle="collapse" data-bs-target="#series"
                            aria-expanded="true" aria-controls="shows" type="button">
                        <i class="fa-solid fa-compact-disc"></i>
                        <span>المسلسلات</span>
                    </button>
                </h2>
                <div id="series" class="accordion-collapse collapse " aria-labelledby="heading3"
                     data-bs-parent="#items">
                    <div class="accordion-body">
                        <div class="sidebar_items">
                            <a href="{{route('admin.series.all.get')}}" class="item">كل المسلسلات</a>
                            <a href="{{route('admin.series.new.get')}}" class="item">اضافة مسلسل</a>
                        </div>
                    </div>
                </div>
            </div>
            <hr class="m-0 p-0"/>

            <div class="accordion-item">
                <h2 class="accordion-header" id="heading4">
                    <button class="sidebar_menu_btn" data-bs-toggle="collapse" data-bs-target="#settings"
                            aria-expanded="true" aria-controls="shows" type="button">
                        <i class="fa-solid fa-gear"></i>
                        <span>الإعدادت</span>
                    </button>
                </h2>
                <div id="settings" class="accordion-collapse collapse " aria-labelledby="heading4"
                     data-bs-parent="#items">
                    <div class="accordion-body">
                        <div class="sidebar_items">
                            <a href="{{route('admin.site_settings.get')}}" class="item">اعدادت الموقع</a>
                            <a href="{{route('admin.site_settings_scripts.get')}}" class="item">إعدادات اكواد الموقع</a>
                        </div>
                    </div>
                </div>
            </div>
            <hr class="m-0 p-0"/>

            <div class="accordion-item">
                <h2 class="accordion-header" id="heading5">
                    <button class="sidebar_menu_btn" data-bs-toggle="collapse" data-bs-target="#bars"
                            aria-expanded="true" aria-controls="shows" type="button">
                        <i class="fa-solid fa-bars"></i>
                        <span>القوائم</span>
                    </button>
                </h2>
                <div id="bars" class="accordion-collapse collapse " aria-labelledby="heading5"
                     data-bs-parent="#items">
                    <div class="accordion-body">
                        <div class="sidebar_items">
                            <a href="{{route('admin.bar.get', ['id' => 1])}}" class="item">أعلى الهيدر</a>
                        </div>
                    </div>
                </div>
            </div>
            <hr class="m-0 p-0"/>

            <div class="accordion-item">
                <h2 class="accordion-header" id="heading6">
                    <button class="sidebar_menu_btn" data-bs-toggle="collapse" data-bs-target="#terms" aria-expanded="true" aria-controls="terms" type="button">
                        <i class="fa-solid fa-map"></i>
                        <span>المصطلحات</span>
                    </button>
                </h2>
                <div id="terms" class="accordion-collapse collapse " aria-labelledby="heading6" data-bs-parent="#items">
                    <div class="accordion-body">
                        <div class="sidebar_items">
                            <a href="{{route('admin.categories.all.get')}}" class="item">الأقسام</a>
                            <a href="{{route('admin.types.all.get')}}" class="item">الأنواع</a>
                            <a href="{{route('admin.qualities.all.get')}}" class="item">الجودات</a>
                        </div>
                    </div>
                </div>
            </div>
            <hr class="m-0 p-0" />

            <div class="accordion-item">
                <h2 class="accordion-header" id="heading7">
                    <button class="sidebar_menu_btn" data-bs-toggle="collapse" data-bs-target="#all_servers" aria-expanded="true" aria-controls="all_servers" type="button">
                        <i class="fa-solid fa-server"></i>
                        <span>ترتيب السيرفرات</span>
                    </button>
                </h2>
                <div id="all_servers" class="accordion-collapse collapse " aria-labelledby="heading7" data-bs-parent="#items">
                    <div class="accordion-body">
                        <div class="sidebar_items">
                            <a href="{{route('admin.watch_servers.all.get')}}" class="item">سيرفرات المشاهدة</a>
                            <a href="{{route('admin.down_servers.all.get')}}" class="item">سيرفرات التحميل</a>
                        </div>
                    </div>
                </div>
            </div>
            <hr class="m-0 p-0" />

            <a class="sidebar_menu_btn" href="{{route('admin.glide.get')}}">
                <i class="fa-brands fa-glide"></i>
                <span>السلايدر</span>
            </a>
            <hr class="m-0 p-0" />

            <a class="sidebar_menu_btn" href="{{route('admin.social_media.get')}}">
                <i class="fa-solid fa-share-nodes"></i>
                <span>السوشيال ميديا</span>
            </a>
            <hr class="m-0 p-0" />

            <a class="sidebar_menu_btn" href="{{route('admin.accounts.all.get')}}">
                <i class="fa-solid fa-user"></i>
                <span>الحسابات</span>
            </a>
            <hr class="m-0 p-0" />

{{--            <a class="sidebar_menu_btn" href="{{route('admin.sitemap.get')}}">--}}
{{--                <i class="fa-solid fa-sitemap"></i>--}}
{{--                <span>خريطة الموقع</span>--}}
{{--            </a>--}}
{{--            <hr class="m-0 p-0" />--}}

            <a class="sidebar_menu_btn" href="{{route('logout')}}">
                <i class="fa-solid fa-right-from-bracket"></i>
                <span>تسجيل الخروج</span>
            </a>
            <hr class="m-0 p-0"/>

        </div>
    </div>

    <div style="width: -webkit-fill-available;">
        @yield('content')
    </div>
</div>

</body>
