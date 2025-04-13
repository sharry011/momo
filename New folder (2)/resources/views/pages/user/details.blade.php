@extends('layouts/main_layout')

@section('title', $post->title . ' - ' . $settings->site_name)
@section('page_keywords', $settings->keywords)
@section('page_description', html_entity_decode($post->story))
@section('page_type', 'article')
@section('page_img', asset($post->img))

@section('head')
    <link rel="stylesheet" href="{{asset('/assets/glide/css/glide.core.min.css')}}"/>
    <link rel="stylesheet" href="{{asset('/assets/glide/css/glide.theme.min.css')}}"/>
    <script src="{{asset('/assets/glide/glide.min.js')}}"></script>
    <style>
        .glide__arrows {
            display: flex;
            justify-content: space-between;
            align-items: center;
            flex-direction: row;
            /* height: 50px; */
            margin-bottom: 25px;
        }

        .glide__arrow {
            position: unset;
            transform: translateY(0);
        }

        .arrows_cont {
            display: flex;
            flex-direction: row-reverse;
        }

        .glide__arrow--right {
            width: 48px;
            height: 48px;
            font-size: 17px;
            color: #fff;
            background: #ed3c3c;
            display: inline-block;
            text-align: center;
            border: none;
            border-radius: 0 50rem 50rem 0;
        }

        .glide__arrow--left {
            width: 48px;
            height: 48px;
            font-size: 17px;
            color: #fff;
            background: #ed3c3c;
            display: inline-block;
            text-align: center;
            border: none;
            border-radius: 50rem 0 0 50rem;
            margin-right: 5px;
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
            <a href="">{{ $post->title }}</a>
        </nav>

        <div class="row">
            <div class="poster-side col-12 col-md-4">
                <div class="poster" style="--background-image-url: url({{asset($post->img)}})">
                    <div class="d-flex flex-row-reverse">
                        <button class="btn btn-trailer m-3 px-4 py-2 fw-bold" data-bs-toggle="modal"
                                data-bs-target="#TrailerModal"
                                style="opacity: {{ $post->triller ? 1 : 0 }}; pointer-events: {{ $post->triller ? 'auto' : 'none' }}">
                            <i class="fa-solid fa-tv"></i>
                            مشاهدة التريلر
                        </button>
                    </div>
                </div>
            </div>
            <div class="info-side col-12 col-md-8">
                <div class="row">
                    <div class="col-12 col-lg-9">
                        <span class="title">{{ $post->title }}</span>
                        <span class="qualities">
                            <div class="d-flex align-items-start mb-2">
                                <span class="btn btn-main cursor-normal ms-2 pr">الجودة</span>
                                <div class="ch">
                                    @foreach ($post->qualities as $quality)
                                        <a href="{{url('/quality/' . $quality->name)}}" class="btn btn-gray mb-2  ms-2">
                                            {{ $quality->name }}
                                        </a>
                                    @endforeach
                                </div>
                            </div>
                        </span>
                        <span class="description">
                            @if ($post->story)
                                {!! $post->story !!}
                            @endif
                        </span>
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
                                @if ($post->year)
                                    <div class="d-flex align-items-start mb-2">
                                        <span class="btn btn-main cursor-normal ms-2 pr">السنة</span>
                                        <div class="ch">
                                            <a href="{{url('/release-year/' . $post->year)}}" class="btn btn-gray mb-2">
                                                {{ $post->year }}
                                            </a>
                                        </div>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                    <div class="actions col-12 col-lg-3">
                        <a href="{{url('/watch/' . $post->slug)}}" class="btn watch">
                            <i class="fa-solid fa-circle-play"></i>
                            <span>مشاهدة الآن</span>
                        </a>
                        <a href="{{url('/download/' . $post->slug)}}" class="btn download">
                            <i class="fa-solid fa-cloud-arrow-down"></i>
                            <span>تحميل الآن</span>
                        </a>
                    </div>
                </div>
                <div class="stuff row">
                    @if (count($post->actors) > 0)
                        <div class="col-12 col-lg-4">
                            <div class="bg-main item">
                                <i class="fa-solid fa-people-group"></i>
                                الممثلين
                            </div>
                            <div class="items-container">
                                @foreach ($post->actors as $person)
                                    <a href="{{url('/actor/' . $person->name)}}" class="item">
                                        <img src="{{asset('photos/imgs/avatar.png')}}" alt=""/>
                                        {{ $person->name }}
                                    </a>
                                @endforeach
                            </div>
                        </div>
                    @endif

                    @if (count($post->writers) > 0)
                        <div class="col-12 col-lg-4">
                            <div class="bg-main item">
                                <i class="fa-solid fa-person-burst"></i>
                                تأليف
                            </div>
                            <div class="items-container">
                                @foreach ($post->writers as $person)
                                    <a href="{{url('/writer/' . $person->name)}}" class="item">
                                        <img src="{{asset('photos/imgs/avatar.png')}}" alt=""/>
                                        {{ $person->name }}
                                    </a>
                                @endforeach
                            </div>
                        </div>
                    @endif

                    @if (count($post->directors) > 0)
                        <div class="col-12 col-lg-4">
                            <div class="bg-main item">
                                <i class="fa-solid fa-person-dots-from-line"></i>
                                إخراج
                            </div>
                            <div class="items-container">
                                @foreach ($post->directors as $person)
                                    <a href="{{url('/director/' . $person->name)}}" class="item">
                                        <img src="{{asset('photos/imgs/avatar.png')}}" alt=""/>
                                        {{ $person->name }}
                                    </a>
                                @endforeach
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        @if ($post->opt == 2)
            <div>
                <div class="w-100 bg-main rounded my-4">
                    <div
                        class="title px-3 py-2 fs-6 fw-bold d-flex align-items-center border-black border-opacity-25 border-0 border-bottom">
                        <i class="fa-solid fa-list-ul fa-flip-horizontal ms-2"></i>
                        جميع الحلقات
                    </div>
                    <div class="items d-flex flex-wrap px-3 py-2 gap-2">

                        @foreach ($post->season->episodes->sortByDesc('num') as $ep)
                            <a href="{{url('/episode/' . $ep->slug)}}" class="epss @if ($ep->id == $post->id) active @endif">
                                <h3 class="bg-black bg-opacity-50 text-center text-white fw-bold rounded">
                                    <span class="fs-6 px-1">الحلقة</span><span class="fs-2">{{ $ep->num }}</span>
                                </h3>
                            </a>
                        @endforeach

                    </div>
                </div>

                <div class="w-100 bg-main rounded my-4">
                    <div
                        class="title px-3 py-2 fs-6 fw-bold d-flex align-items-center border-black border-opacity-25 border-0 border-bottom">
                        <i class="fa-solid fa-list-ul fa-flip-horizontal ms-2"></i>
                        جميع المواسم
                    </div>
                    <div class="items d-flex flex-wrap px-3 py-2 gap-2">

                        @foreach ($post->season->serie->seasons->sortByDesc('num') as $season)
                            <a href="{{url('/season/' . $season->slug)}}"
                               class="epss @if ($season->id == $post->season_id) active @endif">
                                <h3 class="bg-black bg-opacity-50 text-center text-white fw-bold rounded">
                                    <span class="fs-6 px-1">الموسم</span><span class="fs-2">{{ $season->num }}</span>
                                </h3>
                            </a>
                        @endforeach

                    </div>
                </div>
            </div>
        @endif

        <hr class="my-4 text-white"/>

        <div class="glide ltr" x-ref="glideRef">
            <div class="glide__arrows" data-glide-el="controls">
                <div style="
                    background-color: var(--color-main);
                    display: flex;
                    justify-content: center;
                    align-items: center;
                    border-radius: 50rem 0 0 50rem;
                    padding-left: 25px;
                    padding-right: 10px;
                    padding-block: 10px;
                    font-weight: bold;
                    color: white;
                ">
                    <span>مقالات مشابهة</span>
                </div>
                <div class="arrows_cont">
                    <button class="glide__arrow glide__arrow--left" type="button" onclick="glideToLeft()">
                        <i class="fa-solid fa-arrow-left-long"></i>
                    </button>
                    <button class="glide__arrow glide__arrow--right" onclick="glideToRight()">
                        <i class="fa-solid fa-arrow-right-long"></i>
                    </button>
                </div>
            </div>
            <div class="glide__track" data-glide-el="track">
                <ul class="glide__slides">
                    @foreach ($similar as $similar_post)
                        <li class="glide__slide">
                            <div class="glide_post" style="display: block;">
                                @include('components.main_card', ['post' => $similar_post, 'br' => '0'])
                            </div>
                        </li>
                    @endforeach
                </ul>
            </div>
        </div>

        @if ($post->triller)
            <div class="modal fade p-0" id="TrailerModal" tabindex="-1" aria-labelledby="TrailerModalLabel"
                 aria-hidden="true">
                <div class="" style="display: contents;">
                    <div class="modal-dialog modal-dialog-centered modal-xl">
                        <div class="modal-content" style="background: transparent; border: none;">
                            <div class="" style="width: 100%; height: 100%;">
                                <iframe id="trailerIFrame"
                                        style="max-width: 100%; max-height: 70vh; width: 1280px; height: 720px"
                                        data-yt="{{$post->triller}}" src="" title="YouTube video player" frameborder="0"
                                        allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share"
                                        allowfullscreen></iframe>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <script src="{{asset('/bootstrap/js/bootstrap.bundle.min.js')}}"></script>
            <script src="{{asset('/assets/jquery.js')}}"></script>
            <script>
                $(document).ready(function () {
                    const link = $('#trailerIFrame').attr('data-yt');
                    $('#trailerIFrame').attr('src', convertToEmbedLink(link));

                    $('#TrailerModal').on('hidden.bs.modal', function () {
                        const link = $('#trailerIFrame').attr('data-yt');
                        $('#trailerIFrame').attr('src', convertToEmbedLink(link));
                    });
                });
            </script>
        @endif

    </div>

    <script>

        var glide = new Glide('.glide', {
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
                    perView: 1.5
                },
            }
        }).mount();

        function glideToLeft() {
            glide.go('<');
        }

        const glideToRight = () => {
            glide.go('>');
        };
        const convertToEmbedLink = (normalLink) => {
            const url = new URL(normalLink);
            const videoId = url.searchParams.get("v");
            if (videoId) {
                const embedLink = `https://www.youtube.com/embed/${videoId}`;
                return embedLink;
            } else {
                return normalLink;
            }
        }

    </script>
@endsection
