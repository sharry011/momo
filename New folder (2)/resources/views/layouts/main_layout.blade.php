<!DOCTYPE html>
<html lang="ar" dir="rtl">

<head>
    <!-- By MoundherB -->
    <meta charset='utf-8'>
    <meta http-equiv='X-UA-Compatible' content='IE=edge'>
    <meta name='viewport' content='width=device-width, initial-scale=1'>
    <title>@yield('title')</title>

    <link rel="shortcut icon" type="image/png" href="{{asset('faveicon.ico')}}?v={{$settings->icon_index}}"/>

    <link rel="preload"
          href="https://fonts.googleapis.com/css2?family=Tajawal:wght@200;300;400;500;700;800;900&display=swap"
          as="style">
    <link rel="stylesheet"
          href="https://fonts.googleapis.com/css2?family=Tajawal:wght@200;300;400;500;700;800;900&display=swap">

    <link rel='stylesheet' media='screen' href="{{asset('/bootstrap/css/bootstrap.min.css')}}"/>
    <link rel='stylesheet' href="{{asset('/assets/main_style.css')}}?v=5"/>

    <link rel="preload" href="{{asset('/assets/glide/css/glide.core.min.css')}}" as="style">
    <link rel="preload" href="{{asset('/assets/glide/css/glide.theme.min.css')}}" as="style">

    @unless(isset($remove_meta) && $remove_meta)
        <meta name="keywords" content="@yield('page_keywords')"/>
        <meta name="description" content="@yield('page_description')"/>
        <meta property="og:type" content="@yield('page_type')"/>
        <meta property="og:title" content="@yield('title')"/>
        <meta property="og:url" content="{{url()->current()}}"/>
        <meta property="og:image" content="@yield('page_img')"/>
        <meta property="og:image:width" content="400"/>
        <meta property="og:image:height" content="400"/>
        <meta property="og:description" content="@yield('page_description')"/>
        <meta property="og:site_name" content="{{ $settings->site_name }}"/>
        <meta name="twitter:title" content="@yield('title')"/>
        <meta name="twitter:description" content="@yield('page_description')"/>
        <meta name="twitter:image" content="@yield('page_img')"/>
        <meta name="twitter:card" content="summary"/>
    @endunless

    <!-- Other Meta Tags -->
    <meta name="robots" content="index, follow"/>
    <meta name="copyright" content="{{$settings->site_name}}">

    @yield('head')

    {!! $settings_scripts->head !!}

</head>

<body>
<div class="main">

    <div>
        <div class="links w-100 bg-main text-white">
            <div class="p-0 m-0">
                <div class="bar_btn">
                    <button class="btn btn-bar" style="color: white">
                        <i class="fa-solid fa-bars"></i>
                    </button>
                </div>
                <div class="over"></div>
            </div>

            <div class="container mb-0 pb-3 pb-lg-0 ">
                <div class="d-flex flex-wrap justify-content-center bar">

                    <a class="btn btn-hd" href="{{url('/')}}">
                        الرئيسية
                    </a>

                    @if ($header_bar)
                        @foreach ($header_bar->items as $item)
                            @if ($item['type'] == 'multi')
                                <div class="dropdown">
                                    <button class="btn dropdown-toggle" type="button" data-bs-toggle="dropdown1"
                                            aria-expanded="false">
                                        {{$item['name']}}
                                    </button>
                                    <ul class="dropdown-menu">
                                        @foreach ($item['items'] as $sub_item)
                                            <li>
                                                <a class="dropdown-item"
                                                   href="{{ url($sub_item['type'] == 'categ' ? '/category/' . $sub_item['slug'] : $sub_item['link']) }}">
                                                    {{ $sub_item['name'] }}
                                                </a>
                                            </li>
                                        @endforeach
                                    </ul>
                                </div>
                            @else
                                <a class=" btn btn-hd"
                                   href="{{url($item['item']['type'] == 'categ' ? '/category/' . $item['item']['slug'] : $item['item']['link'])}}">
                                    {{$item['item']['name']}}
                                </a>
                            @endif
                        @endforeach
                    @endif
                </div>

                <script>
                    function openBar() {
                        document.querySelector('.bar').classList.add('show');
                        document.querySelector('.over').classList.add('show');
                    }

                    function closeBar() {
                        document.querySelector('.bar').classList.remove('show');
                        document.querySelector('.over').classList.remove('show');
                    }

                    document.querySelector('.bar_btn').addEventListener('click', openBar);
                    document.querySelector('.over').addEventListener('click', closeBar);
                </script>

                <div class="social-media">
                    @if($social_media )
                        @foreach ($social_media->filter(function ($item) { return $item->id != 1; }) as $sm)
                            <a href="{{ $sm->link }}" target="_blank" class="btn btn-social-media"
                               aria-label="{{ $sm->name }}" title="{{ $sm->name }}">
                                {!! $sm->icon !!}
                            </a>
                        @endforeach
                    @endif
                </div>

            </div>
        </div>
        <div class="logo-section w-100 bg-gray1"
             style="box-shadow: 0 7px 4px #000000a3; border-bottom: 1px rgb(255 255 255 / 30%) solid">
            <div class="container logo py-2">
                <div class="logo--area"
                     style="min-width: {{$settings->site_logo['style']['min']}}px; max-width: {{$settings->site_logo['style']['max']}}px">
                    <h1>
                        <a href="{{url('/')}}" title="{{$settings->site_name}}">
                            <div class="en-text">
                                    <span>
                                        {{$settings->site_logo['en']['t1']}}
                                    </span><span>
                                        {{$settings->site_logo['en']['t2']}}
                                    </span><span>
                                        {{$settings->site_logo['en']['t3']}}
                                    </span>
                            </div>
                            <div class="ar-text">
                                    <span>
                                        {{$settings->site_logo['ar']['t1']}}
                                    </span><span>
                                        {{$settings->site_logo['ar']['t2']}}
                                    </span>
                            </div>
                        </a>
                    </h1>
                </div>
                <form class="search-container" action="{{url('/search')}}" method="get">
                    <input type="text" class="search-input form-control ci" placeholder="ابحث في الموقع" name="s">
                    <button type="submit" class="search-button btn btn-imain fw-bold">بحث</button>
                </form>
            </div>

        </div>
    </div>

    @yield('content')

    <div class="footer">
        {{$settings->site_footer}}
        <br/>
        <i class="fa-regular fa-copyright" style="font-size: 13px;"></i> {{ date('Y') }}
    </div>
</div>
</body>

{!! $settings_scripts->fotter !!}

<script>
    function loadCSS(href) {
        const style = document.createElement("link");
        style.href = href;
        style.rel = "stylesheet";
        document.head.appendChild(style);
    }

    loadCSS(
        "https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css"
    );
</script>

</html>
