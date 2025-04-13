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
        .item-title {
            background-color: var(--color-gray1);
            color: white;
            font-size: 14px;
            padding-block: 15px;
            font-weight: 600;
            margin-bottom: 10px;
            text-align: center;
        }

        .back {
            width: 100%;
            background-color: var(--color-gray1);
            border-radius: 5px;
            margin: 0;
        }

        .btn-server {
            background-color: var(--color-pr);
            outline: none;
            border: none;
            color: white;
        }

        .btn-server:active,
        .btn-server.active,
        .btn-server:hover {
            background-color: var(--color-pr2);
            background-color: var(--color-main);
            color: white;
        }

        .btn-ep {
            background-color: #111522;
            color: white;
            border: 0;
            border-bottom: 1px solid #343843;
            padding-block: 8px !important;
        }

        .btn-ep:active,
        .btn-ep.active,
        .btn-ep:hover {
            background-color: var(--color-pr2);
            background-color: var(--color-main);
            color: white;
        }

        .server_img {
            width: 35px;
            height: 35px;
            border-radius: 50%;
            margin-inline-end: 5px;
        }

        #watch-container {
            background-color: var(--color-gray2);
            margin-block: 30px;
        }

        #player {
            height: 560px;
        }

        .items_container {
            max-height: 560px;
            overflow-y: auto;
            /*
            scrollbar-width: thin;
            scrollbar-color: var(--color-gray2) var(--color-main);
             */
        }

        .items_container::-webkit-scrollbar {
            width: 6px;
            /* Set the width of the scrollbar */
        }

        .items_container::-webkit-scrollbar-track {
            background-color: var(--color-muted);
            /* Set the background color of the scrollbar track */
        }

        .items_container::-webkit-scrollbar-thumb {
            background-color: white;
            /* Set the color of the scrollbar thumb */
        }
    </style>
@endsection

@section('content')
    <div class="container">
        <nav class="bc fs-6 fw-bold text-white">
            <a href="/">الرئيسية</a>
            @if ($post->categories->count() > 0)
                <i class="fa-solid fa-arrow-left"></i>
                <a href="/category/{{ $post->categories[0]->slug }}">{{ $post->categories[0]->name }}</a>
            @endif

            @if ($post->opt == 2)
                <i class="fa-solid fa-arrow-left"></i>
                <a href="/series/{{ $post->season->serie->slug }}">{{ $post->season->serie->title }}</a>
                <i class="fa-solid fa-arrow-left"></i>
                <a href="/season/{{ $post->season->slug }}">{{ $post->season->title }}</a>
            @endif

            <i class="fa-solid fa-arrow-left"></i>
            <a href="{{$link}}">{{ $post->title }}</a>

            <i class="fa-solid fa-arrow-left"></i>
            <a href="">صالة العرض</a>
        </nav>

        <div class="back row">
            <div class="col-6 d-flex justify-content-start">
                <a href="{{url($link)}}" class="btn btn-main m-3 px-4 py-2 fw-bold">
                    <i class="fa-solid fa-tv"></i>
                    العودة للتفاصيل
                </a>
            </div>
            <div class="col-6 d-flex justify-content-end">
                <a href="{{url('/download/' . $post->slug)}}" class="btn btn-main m-3 px-4 py-2 fw-bold">
                    <i class="fa-solid fa-download"></i>
                    تحميل الآن
                </a>
            </div>
        </div>

        <div id="watch-container">
            <div class="row m-0 p-0">
                <div id="servers" class="col-12 col-lg-2 p-0">
                    <div class="item-title w-lg-100">
                        سيرفرات المشاهدة
                    </div>
                    <div class="row px-2 items_container ltr">
                        @foreach ($post->watch_servers as $index => $server)
                            <div class="col-6 col-md-12 rtl px-3 py-1">
                                <button
                                    class="btn btn-server w-100 mb-2 px-3 py-1 fw-bold d-flex justify-content-start align-items-center {{ $index === 0 ? 'active' : '' }}"
                                    data-index="{{ $index }}">
                                    @if (!$server['img'])
                                        <i class="fa-solid fa-circle-play fs-2 ms-2"></i>
                                    @else
                                        <img title="{{$server['name']}}" src="{{ url($server['img']) }}" class="server_img"/>
                                    @endif
                                    <span class="fw-bold">{{ $server['name'] }}</span>
                                </button>
                            </div>
                        @endforeach
                    </div>
                </div>
                <div id="player" class="col-12 {{ $post->opt == 2 ? 'col-lg-8' : 'col-lg-10' }} p-0">
                    <iframe src="" frameborder="0" width="100%" height="100%" allowfullscreen="true"
                            scrolling="no"></iframe>
                </div>
                @if ($post->opt == 2)
                    <div id="eps" class="col-12 col-lg-2 p-0">
                        <div class="item-title w-lg-100 m-0">
                            جميع الحلقات
                        </div>
                        <div class="d-flex flex-column items_container">
                            @foreach ($post->season->episodes->sortByDesc('num') as $ep)
                                <a href="{{url('/watch/' . $ep->slug)}}"
                                   class="btn btn-ep w-lg-100 br-0 px-3 py-1 fw-bold d-flex justify-content-start align-items-center {{ $ep->id == $post->id ? 'active' : '' }}">
                                    <span class="fw-bold" style="font-size: 18px;">الحلقة {{ $ep->num }}</span>
                                </a>
                            @endforeach
                        </div>
                    </div>
                @endif
            </div>
        </div>

    </div>


    <script src="{{asset('/assets/jquery.js')}}"></script>
    <script>
        let servers = JSON.parse('@json($post->watch_servers)');
        $('#player').find('iframe').attr('src', servers[0].url);

        $('.btn-server').click(function () {
            $('.btn-server').removeClass('active');
            $(this).addClass('active');
            let index = $(this).data('index');
            let url = servers[index].url;

            // Remove the existing iframe
            $('#player').find('iframe').remove();

            // Create a new iframe element
            let newIframe = document.createElement('iframe');

            // Set attributes for the new iframe
            newIframe.setAttribute('src', url);
            newIframe.setAttribute('frameborder', '0');
            newIframe.setAttribute('width', '100%');
            newIframe.setAttribute('height', '100%');
            newIframe.setAttribute('allowfullscreen', 'true');
            newIframe.setAttribute('scrolling', 'no');

            // Append the new iframe to the #player element
            $('#player').append(newIframe);
        });
    </script>
@endsection
