@extends('layouts/main_layout')

@section('title', $serie->title . ' - ' . $settings->site_name)
@section('page_keywords', $settings->keywords)
@section('page_description', html_entity_decode($serie->story))
@section('page_type', 'article')
@section('page_img', asset($serie->img))


@section('content')
    <div class="container">
        <nav class="bc fs-6 fw-bold text-white">
            <a href="{{url('/')}}">الرئيسية</a>
            @if ($serie->categories->count() > 0)
                <i class="fa-solid fa-arrow-left"></i>
                <a href="{{url('/category/' . $serie->categories[0]->slug)}}">{{ $serie->categories[0]->name }}</a>
            @endif
            <i class="fa-solid fa-arrow-left"></i>
            <a href="">{{ $serie->title }}</a>
        </nav>

        <div class="row">
            <div class="poster-side col-12 col-md-4">
                <div class="poster" style="--background-image-url: url({{asset($serie->img)}})">
                    <div class="d-flex flex-row-reverse">
                        <button class="btn btn-trailer m-3 px-4 py-2 fw-bold" data-bs-toggle="modal"
                                data-bs-target="#TrailerModal"
                                style="opacity: {{ $serie->triller ? 1 : 0 }}; pointer-events: {{ $serie->triller ? 'auto' : 'none' }}">
                            <i class="fa-solid fa-tv"></i>
                            مشاهدة التريلر
                        </button>
                    </div>
                </div>
            </div>
            <div class="info-side col-12 col-md-8">
                <div class="row">
                    <div class="col-12 col-lg-9">
                        <span class="title">{{ $serie->title }}</span>
                        <span class="qualities">
                            <div class="d-flex align-items-start mb-2">
                                <span class="btn btn-main cursor-normal ms-2 pr">الجودة</span>
                                <div class="ch">
                                    @foreach ($serie->qualities as $quality)
                                        <a href="{{url('/quality/' . $quality->name)}}" class="btn btn-gray mb-2  ms-2">
                                            {{ $quality->name }}
                                        </a>
                                    @endforeach
                                </div>
                            </div>
                        </span>
                        <span class="description">
                            @if ($serie->story)
                                {!! $serie->story !!}
                            @endif
                        </span>
                        <div class="row">
                            <div class="col-12 col-md-7">
                                <div class="d-flex align-items-start mb-2">
                                    <span class="btn btn-main cursor-normal ms-2 pr">القسم</span>
                                    <div class="ch">
                                        @foreach ($serie->categories as $categ)
                                            <a href="{{url('/category/' . $categ->slug)}}" class="btn btn-gray mb-2">
                                                {{ $categ->name }}
                                            </a>
                                        @endforeach
                                    </div>
                                </div>
                                <div class="d-flex align-items-start mb-2">
                                    <span class="btn btn-main cursor-normal ms-2 pr">النوع</span>
                                    <div class="ch">
                                        @foreach ($serie->types as $serie_type)
                                            <a href="{{url('/genre/' . $serie_type->name)}}" class="btn btn-gray mb-2 ms-2">
                                                {{ $serie_type->name }}
                                            </a>
                                        @endforeach
                                    </div>
                                </div>
                            </div>
                            <div class="col-12 col-md-5">
                                @if ($serie->year)
                                    <div class="d-flex align-items-start mb-2">
                                        <span class="btn btn-main cursor-normal ms-2 pr">السنة</span>
                                        <div class="ch">
                                            <a href="{{url('/release-year/' . $serie->year)}}" class="btn btn-gray mb-2">
                                                {{ $serie->year }}
                                            </a>
                                        </div>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                    <div class="actions col-12 col-lg-3">
                        <a href="{{url('/series/' . $serie->slug . '/seasons')}}" class="btn watch">
                            <i class="fa-solid fa-circle-play"></i>
                            <span style="font-size: 18px;">عرض جميع المواسم</span>
                        </a>
                    </div>
                </div>
                <div class="stuff row">
                    @if (count($serie->actors) > 0)
                        <div class="col-12 col-lg-4">
                            <div class="bg-main item">
                                <i class="fa-solid fa-people-group"></i>
                                الممثلين
                            </div>
                            <div class="items-container">
                                @foreach ($serie->actors as $person)
                                    <a href="{{url('/actor/' . $person->name)}}" class="item">
                                        <img src="{{asset('/photos/imgs/avatar.png')}}" alt=""/>
                                        {{ $person->name }}
                                    </a>
                                @endforeach
                            </div>
                        </div>
                    @endif

                    @if (count($serie->writers) > 0)
                        <div class="col-12 col-lg-4">
                            <div class="bg-main item">
                                <i class="fa-solid fa-person-burst"></i>
                                تأليف
                            </div>
                            <div class="items-container">
                                @foreach ($serie->writers as $person)
                                    <a href="{{url('/writer/' . $person->name)}}" class="item">
                                        <img src="{{asset('/photos/imgs/avatar.png')}}" alt=""/>
                                        {{ $person->name }}
                                    </a>
                                @endforeach
                            </div>
                        </div>
                    @endif

                    @if (count($serie->directors) > 0)
                        <div class="col-12 col-lg-4">
                            <div class="bg-main item">
                                <i class="fa-solid fa-person-dots-from-line"></i>
                                إخراج
                            </div>
                            <div class="items-container">
                                @foreach ($serie->directors as $person)
                                    <a href="{{url('/director/' . $person->name)}}" class="item">
                                        <img src="{{asset('/photos/imgs/avatar.png')}}" alt=""/>
                                        {{ $person->name }}
                                    </a>
                                @endforeach
                            </div>
                        </div>
                    @endif
                </div>
            </div>

        </div>

        <div>

            <div class="w-100 bg-main rounded my-4">
                <div
                    class="title px-3 py-2 fs-6 fw-bold d-flex align-items-center border-black border-opacity-25 border-0 border-bottom">
                    <i class="fa-solid fa-list-ul fa-flip-horizontal ms-2"></i>
                    جميع المواسم
                </div>
                <div class="items d-flex flex-wrap px-3 py-2 gap-2">

                    @foreach ($serie->seasons as $s_serie)
                        <a href="{{'/season/' . $s_serie->slug}}"
                           class="epss @if ($serie->id == $s_serie->id) active @endif">
                            <h3 class="bg-black bg-opacity-50 text-center text-white fw-bold rounded">
                                <span class="fs-6 px-1">الموسم</span><span class="fs-2">{{ $s_serie->num }}</span>
                            </h3>
                        </a>
                    @endforeach

                </div>
            </div>

        </div>
    </div>
@endsection
