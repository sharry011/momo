@extends('layouts/main_layout')

@section('title', $settings->site_name)
@section('page_keywords', $settings->keywords)
@section('page_description', $settings->site_desc)
@section('page_type', 'website')
@section('page_img', asset('/faveicon.ico'))

@section('head')
<link rel="stylesheet" href="{{asset('/assets/glide/css/glide.core.min.css')}}" />
<link rel="stylesheet" href="{{asset('/assets/glide/css/glide.theme.min.css')}}" />
<script src="{{asset('/assets/glide/glide.min.js')}}"></script>
<style>
    .glide__arrow--right {
        position: absolute;
        right: 0;
        border-radius: 50px 0 0 50px;
        background: var(--color-main);
        color: #fff;
        text-align: center;
        width: 55px;
        height: 48px;
        line-height: 0;
        border: none;
    }

    .glide__arrow--left {
        position: absolute;
        left: 0;
        border-radius: 0 50px 50px 0;
        background: var(--color-main);
        color: #fff;
        text-align: center;
        width: 55px;
        height: 48px;
        line-height: 0;
        border: none;
    }

    .fillter * {
        color: #fff;
        text-decoration: none;
        font-size: 14px;
        font-weight: 600;
        margin: 0;
    }

    .fillter {
        background: #1d1d30;
        display: flex;
        flex-wrap: nowrap;
        align-items: center;
        border-radius: 10rem 50rem 50rem 10rem;
        margin: 10px 5px;
        cursor: pointer;
        transition: all 0.3s ease-in-out;
    }

    .fillter.active,
    .fillter:hover {
        background: var(--color-main);
        transition: all 0.2s ease-in-out;
    }

    .fillter.active .icon img,
    .fillter:hover .icon img {
        /* outline: #fff solid 1px; */
        transition: all 0.2s ease-in-out;
    }

    .fillter h3 {
        padding-inline-start: 5px;
    }

    .fillter .icon {
        padding: 3px;
    }

    .fillter .icon img {
        display: flex;
        justify-content: center;
        align-items: center;
        height: 55px;
        border-radius: 50%;
    }
</style>
@endsection

@section('content')

<div class="glide ltr" ref="glideRef">
    <div class="glide__track" data-glide-el="track">
        <ul class="glide__slides">
            @foreach ($glide_posts as $glide_post)
            <li class="glide__slide">
                <div class="glide_post" style="display: block;">
                    @include('components.main_card', ['post' => $glide_post->post, 'br' => '0'])
                </div>
            </li>
            @endforeach
        </ul>
    </div>
    <div class="glide__arrows" data-glide-el="controls">
        <button class="glide__arrow glide__arrow--left" data-glide-dir="<" title="left" id="glide_left_btn">
            <i class="fa-solid fa-arrow-left-long"></i>
        </button>
        <button class="glide__arrow glide__arrow--right" data-glide-dir=">" title="right" id="glide_right_btn">
            <i class="fa-solid fa-arrow-right-long"></i>
        </button>
    </div>
</div>
<span class="d-block border border-3 border-top-0 border-main"></span>

<div class="content container mt-5">

    <div class="simple-filter row">
        <!-- Category Block -->
        <div class="col-6 col-md-4 col-lg-2">
            <div class="fillter @if(!request()->query('order') || request()->query('order') == 'last') active @endif"
                onclick="window.location = '?order=last' ">
                <div class="icon">
                    <img src="{{asset('photos/imgs/new.png')}}" height="55" width="55" title="الأحدث">
                </div>
                <h3>الأحدث</h3>
            </div>
        </div>
        <!-- Category Block -->
        <div class="col-6 col-md-4 col-lg-2">
            <div class="fillter @if(request()->query('order') == 'rating') active @endif"
                onclick="window.location = '?order=rating' ">
                <div class="icon">
                    <img src="{{asset('/photos/imgs/topratting.png')}}" height="55" width="55" title="الأعلى تقيماً">
                </div>
                <h3>الأعلى تقيماً</h3>
            </div>
        </div>
        <!-- Category Block -->
        <div class="col-6 col-md-4 col-lg-2">
            <div class="fillter @if(request()->query('order') == 'views') active @endif"
                onclick="window.location = '?order=views' ">
                <div class="icon">
                    <img src="{{asset('/photos/imgs/mostviews.png')}}" height="55" width="55" title="الأكثر مشاهدة">
                </div>
                <h3>الأكثر مشاهدة</h3>
            </div>
        </div>
        <!-- Category Block -->
        <div class="col-6 col-md-4 col-lg-2">
            <div class="fillter @if(request()->query('order') == 'pin_index') active @endif"
                onclick="window.location = '?order=pin_index' ">
                <div class="icon">
                    <img src="{{asset('photos/imgs/pinned.png')}}" height="55" width="55" title="المثبت">
                </div>
                <h3>المثبت</h3>
            </div>
        </div>
        <!-- Category Block -->
        <div class="col-6 col-md-4 col-lg-2">
            <div class="fillter @if(request()->query('order') == 'last_films') active @endif"
                onclick="window.location = '?order=last_films' ">
                <div class="icon">
                    <img src="{{asset('photos/imgs/lastmovies.png')}}" height="55" width="55" title="جديد الافلام">
                </div>
                <h3>جديد الافلام</h3>
            </div>
        </div>
        <!-- Category Block -->
        <div class="col-6 col-md-4 col-lg-2">
            <div class="fillter @if(request()->query('order') == 'last_eps') active @endif"
                onclick="window.location = '?order=last_eps' ">
                <div class="icon">
                    <img src="{{asset('photos/imgs/lasteps.png')}}" height="55" width="55" title="جديد الحلقات">
                </div>
                <h3>جديد الحلقات</h3>
            </div>
        </div>
    </div>

    <div class="adv-filter py-3">
        <div class="py-4 px-3 w-100 bg-gray1 rounded">
            <div class="row">
                <div class="col-12 col-md-3 col-lg-1 d-flex align-items-center mb-2">
                    <p class="text-white fw-bold m-0">التصفية</p>
                </div>
                <div class="col-12 col-md-3 col-lg-3 mb-2">
                    <select class="form-select ci w-100" aria-label="Default select example"
                        onchange="updateQuery('category', this.value)">
                        <option selected value="">كل الاقسام</option>
                        @foreach ($categories as $item)
                            <option value="{{ $item->slug }}"
                                @if (request()->query('category') == $item->slug) selected @endif>
                                {{ $item->name }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-12 col-md-3 col-lg-3 mb-2">
                    <select class="form-select ci w-100" aria-label="Default select example"
                        onchange="updateQuery('genre', this.value)">
                        <option selected value="">كل الأنواع</option>
                        @foreach ($types as $item)
                            <option value="{{ $item->name }}"
                                @if (request()->query('genre') == $item->name) selected @endif>
                                {{ $item->name }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-12 col-md-3 col-lg-3 mb-2">
                    <select class="form-select ci w-100" aria-label="Default select example"
                        onchange="updateQuery('year', this.value)">
                        <option selected value="">كل السنوات</option>
                        @foreach ($years as $item)
                            <option value="{{ $item }}"
                                @if (request()->query('year') == $item) selected @endif>
                                {{ $item }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-12 col-md-3 col-lg-2 mb-2">
                    <select class="form-select ci w-100" aria-label="Default select example"
                        onchange="updateQuery('quality', this.value)">
                        <option selected value="">كل الجودات</option>
                        @foreach ($qualities as $item)
                            <option value="{{ $item->name }}"
                                @if (request()->query('quality') == $item->name) selected @endif>
                                {{ $item->name }}
                            </option>
                        @endforeach
                    </select>
                </div>
            </div>
        </div>
    </div>

    <div class="shows-container row">
        @foreach ($posts as $post)
        <div class="col-6 col-md-4 col-lg-20ps mb-3">
            @include('components.main_card', ['post' => $post, 'br' => '10px'])
        </div>
        @endforeach
    </div>

    <nav aria-label="Page navigation" class="d-flex justify-content-center">
        <ul class="pagination">
            @if (intval($pagination['currentPage']) > 1)
                <li class="page-item">
                    <button class="page-link cursor-pointer" type="button" aria-label="Page Button"
                        onclick="updateQuery('page', {{ intval($pagination['currentPage']) - 1 }})">
                        <i class="fa-solid fa-forward"></i>
                    </button>
                </li>
            @endif

            @if (intval($pagination['currentPage']) > 1)
                <li class="page-item">
                    <button class="page-link cursor-pointer" type="button" aria-label="Page Button"
                        onclick="updateQuery('page', {{ intval($pagination['currentPage']) - 1 }})">
                        {{ intval($pagination['currentPage']) - 1 }}
                    </button>
                </li>
            @endif

            <li class="page-item">
                <button class="page-link cursor-normal" type="button" aria-label="Page Button"
                    style="background-color: var(--color-main);">
                    {{ $pagination['currentPage'] }}
                </button>
            </li>

            @if (intval($pagination['currentPage']) < intval($pagination['lastPage']))
                <li class="page-item">
                    <button class="page-link cursor-pointer" type="button" aria-label="Page Button"
                        onclick="updateQuery('page', {{ intval($pagination['currentPage']) + 1 }})">
                        {{ intval($pagination['currentPage']) + 1 }}
                    </button>
                </li>
            @endif

            @if (intval($pagination['currentPage']) < intval($pagination['lastPage']))
                <li class="page-item">
                    <button class="page-link cursor-pointer" type="button" aria-label="Page Button"
                        onclick="updateQuery('page', {{ intval($pagination['currentPage']) + 1 }})">
                        <i class="fa-solid fa-backward"></i>
                    </button>
                </li>
            @endif
        </ul>
    </nav>

</div>

<script>
    const updateQuery = (key, value) => {
        const urlParams = new URLSearchParams(window.location.search);
        urlParams.set(key, value);
        if (key != 'page') urlParams.delete('page');
        window.location.search = urlParams;
    }
    new Glide('.glide', {
        type: 'carousel',
        autoplay: 7000,
        animationDuration: 1000,
        hoverpause: true,
        perView: 6,
        direction: 'rtl',
        rewind: true,
        gap: 0,
        breakpoints: {
            1300: {
                perView: 5
            },
            1024: {
                perView: 4
            },
            990: {
                perView: 3
            },
            650: {
                perView: 2
            },
            370: {
                perView: 1
            },
        }
    }).mount()
</script>

@endsection
