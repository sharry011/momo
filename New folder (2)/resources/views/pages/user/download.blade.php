@extends('layouts/main_layout')

@section('title', $post->title . ' - ' . $settings->site_name)
@section('$remove_meta', true)

@php
    $link = '';
    if ($post->opt == 1) {
        $link = '/film/' . $post->slug;
    } else if ($post->opt == 2) {
        $link = '/episode/' . $post->slug;
    } else if ($post->opt == 3) {
        $link = '/post/' . $post->slug;
    }
@endphp

@section('head')
    <style>
        .qual {
            color: white;
            width: 100%;
            text-align: center;
            display: flex;
            justify-content: center;
            align-items: center;
        }

        .qual h1 {
            background-color: var(--color-main);
            width: fit-content;
            padding: 13px 20px;
            border-radius: 50rem;

        }

        .down-container {
            background-color: rgba(41, 50, 60, 0.5);
            border-radius: 10px;
        }

        .down-container .title {
            color: white;
            font-size: 22px;
            font-weight: 700;
            margin-bottom: 1rem;
            width: 100%;
            background-color: var(--color-main);
            text-align: center;
            border-radius: 10px 10px 0 0;
            padding-block: 15px;
        }

        .btn-down {
            background: #00000099;
            display: flex;
            flex-direction: row;
            flex-wrap: nowrap;
            padding: 0px;
            align-items: center;
            justify-content: center;
        }

        .btn-down .icon {
            padding-inline-end: 0.5rem;
            border-radius: 0.5rem;
            margin: 0.5rem;
            color: var(--color-main);
        }

        .btn-down .icon i {
            font-size: 2rem;
        }

        .btn-down .info {
            display: flex;
            flex-direction: column;
            flex-wrap: nowrap;
            justify-content: center;
            color: white;
            /* margin: 0.5rem; */
        }

        .btn-down .info span:first-child {
            font-size: 20px;
            font-weight: bold;
        }

        .btn-down .info span:last-child {
            font-size: 15px;
            font-weight: bold;
            color: white;
        }

        .btn-down .info span:last-child span {
            color: var(--color-gold);
            margin-inline: 5px;
        }

        .btn-down:hover {
            background: var(--color-gold);
            color: white;
        }

        .btn-down:hover .info span:first-child,
        .btn-down:hover .icon {
            color: #6f4308;
        }

        .btn-down .info span:last-child span {
            color: white;
        }

        .info-container {
            background-color: #29323c80;
            padding: 10px;
            border-radius: 10px;
            margin: 0;
            margin-bottom: 2rem;
        }

        .btn.download {
            background-color: var(--color-gold) !important;
        }

        .poster::before {
            z-index: 0;
        }
    </style>
@endsection

@section('content')
    <div class="container">
        <nav class="bc fs-6 fw-bold text-white">
            <a href="{{url('/')}}">الرئيسية</a>
            @if ($post->categories->count() > 0)
                <i class="fa-solid fa-arrow-left"></i>
                <a href="{{url('/category/' . $post->categories[0]->slug)}}">{{ $post->categories[0]->name }}</a>
            @endif

            @if ($post->opt == 2)
                <i class="fa-solid fa-arrow-left"></i>
                <a href="{{url('/series/' . $post->season->serie->slug)}}">{{ $post->season->serie->title }}</a>
                <i class="fa-solid fa-arrow-left"></i>
                <a href="{{url('/season/' . $post->season->slug)}}">{{ $post->season->title }}</a>
            @endif

            <i class="fa-solid fa-arrow-left"></i>
            <a href="{{url($link)}}">{{ $post->title }}</a>

            <i class="fa-solid fa-arrow-left"></i>
            <a href="">مركز التحميل</a>
        </nav>

        <div class="down-container">
            <div class="title">
                سيرفرات التحميل
            </div>
            <div class="servers">
                @foreach ($post->down_servers as $size)
                    <div class="qual">
                        <h1 class="text-center fw-bold fs-5">
                            <i class="fa-solid fa-circle-play fs-6"></i>
                            {{ $size['size'] == 10000 ? 'سيرفر تحميل مميز متعدد الجودات' : 'سيرفرات تحميل ' . $size['size'] }}
                        </h1>
                    </div>
                    <div class="row justify-content-center m-0">
                        @foreach ($size['servers'] as $server)
                            <div class="col-12 col-sm-6 col-md-4 col-lg-3 p-2 ">

                                <a href="{{ $server['code'] }}" target="_blank" class="btn btn-down {{$size['size'] == 10000 ? 'py-4' : ''}}">
                                    <div class="icon">
                                        <i class="fa-solid fa-download"></i>
                                    </div>
                                    <div class="info {{$size['size'] == 10000 ? 'flex-column-reverse' : ''}}">
                                        <span>{{ $server['name'] }}</span>
                                        <span>
                                            <i class="fa-solid fa-tv m-0"></i>
                                            @if($size['size'] == 10000)
                                                <span class="fs-5" style="display: contents">متعدد الجودات</span>
                                            @else
                                                <span>{{$size['size']}}</span>
                                            @endif
                                        </span>
                                    </div>
                                </a>
                            </div>
                        @endforeach
                    </div>
                    <hr/>
                @endforeach
            </div>
        </div>

        <hr class="my-5 text-white"/>

        <div class="row info-container">
            <div class="poster-side col-12 col-md-3">
                <div class="poster" style="--background-image-url: url({{asset($post->img)}})">
                    <div class="d-flex flex-row-reverse">
                        <button style="opacity: 0; cursor: auto;">التريلر</button>
                    </div>
                </div>
            </div>
            <div class="info-side col-12 col-md-9">
                <div class="row">
                    <div class="col-12 col-lg-9">
                        <span class="title">{{ $post->title }}</span>
                        <span class="qualities">
                    <div class="d-flex align-items-start mb-2">
                        <span class="btn btn-main cursor-normal ms-2 pr">الجودة</span>
                        <div class="ch">
                            @foreach ($post->qualities as $quality)
                                <a href="{{'/quality/' . $quality->name}}" class="btn btn-gray mb-2 ms-2">
                                    {{ $quality->name }}
                                </a>
                            @endforeach
                        </div>
                    </div>
                </span>
                        <span class="description">
                            @if ($post->story)
                                {!! $post->story !!}
                            @endif</span>
                        <div class="row">
                            <div class="col-12 col-md-7">
                                <div class="d-flex align-items-start mb-2">
                                    <span class="btn btn-main cursor-normal ms-2 pr">القسم</span>
                                    <div class="ch">
                                        @foreach ($post->categories as $categ)
                                            <a href="{{url('/category/' . $categ->slug)}}" class="btn btn-gray mb-2">
                                                {{ $categ->name }}
                                            </a>
                                        @endforeach
                                    </div>
                                </div>
                                <div class="d-flex align-items-start mb-2">
                                    <span class="btn btn-main cursor-normal ms-2 pr">النوع</span>
                                    <div class="ch">
                                        @foreach ($post->types as $post_type)
                                            <a href="{{url('/genre/' . $post_type->name)}}" class="btn btn-gray mb-2 ms-2">
                                                {{ $post_type->name }}
                                            </a>
                                        @endforeach
                                    </div>
                                </div>
                            </div>
                            <div class="col-12 col-md-5">
                                <div class="d-flex align-items-start mb-2">
                                    <span class="btn btn-main cursor-normal ms-2 pr">السنة</span>
                                    <div class="ch">
                                        <a href="{{url('/release-year/' . $post->year)}}" class="btn btn-gray mb-2">
                                            {{ $post->year }}
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="actions col-12 col-lg-3">
                        <a href="{{url('/watch/' . $post->slug)}}" class="btn watch">
                            <i class="fa-solid fa-circle-play"></i>
                            <span>مشاهدة الآن</span>
                        </a>
                        <a href="{{url($link)}}" class="btn download">
                            <i class="fa-solid fa-ticket"></i>
                            <span>العودة للتفاصيل</span>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
