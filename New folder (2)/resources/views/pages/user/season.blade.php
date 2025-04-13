@extends('layouts/main_layout')

@section('title', $season->title . ' - ' . $settings->site_name)
@section('page_keywords', $settings->keywords)
@section('page_description', html_entity_decode($season->story))
@section('page_type', 'article')
@section('page_img', asset($season->img))


@section('content')
    <div class="container">
        <nav class="bc fs-6 fw-bold text-white">
            <a href="{{url('/')}}">الرئيسية</a>
            @if ($season->categories->count() > 0)
                <i class="fa-solid fa-arrow-left"></i>
                <a href="{{url('/category/' . $season->categories[0]->slug)}}">{{ $season->categories[0]->name }}</a>
            @endif
            <i class="fa-solid fa-arrow-left"></i>
            <a href="{{url('/series/' . $season->serie->slug)}}">{{ $season->serie->title }}</a>
            <i class="fa-solid fa-arrow-left"></i>
            <a href="">{{ $season->title }}</a>
        </nav>

        <div class="row">
            <div class="poster-side col-12 col-md-4">
                <div class="poster" style="--background-image-url: url({{asset($season->img)}})">
                    <div class="d-flex flex-row-reverse">
                        <button class="btn btn-trailer m-3 px-4 py-2 fw-bold" data-bs-toggle="modal"
                                data-bs-target="#TrailerModal"
                                style="opacity: {{ $season->triller ? 1 : 0 }}; pointer-events: {{ $season->triller ? 'auto' : 'none' }}">
                            <i class="fa-solid fa-tv"></i>
                            مشاهدة التريلر
                        </button>
                    </div>
                </div>
            </div>
            <div class="info-side col-12 col-md-8">
                <div class="row">
                    <div class="col-12 col-lg-9">
                        <span class="title">{{ $season->title }}</span>
                        <span class="qualities">
                            <div class="d-flex align-items-start mb-2">
                                <span class="btn btn-main cursor-normal ms-2 pr">الجودة</span>
                                <div class="ch">
                                    @foreach ($season->qualities as $quality)
                                        <a href="{{url('/quality/' . $quality->name)}}" class="btn btn-gray mb-2  ms-2">
                                            {{ $quality->name }}
                                        </a>
                                    @endforeach
                                </div>
                            </div>
                        </span>
                        <span class="description">
                            @if ($season->story)
                                {!! $season->story !!}
                            @endif
                        </span>
                        <div class="row">
                            <div class="col-12 col-md-7">
                                <div class="d-flex align-items-start mb-2">
                                    <span class="btn btn-main cursor-normal ms-2 pr">القسم</span>
                                    <div class="ch">
                                        @foreach ($season->categories as $categ)
                                            <a href="{{url('/category/' . $categ->slug)}}" class="btn btn-gray mb-2">
                                                {{ $categ->name }}
                                            </a>
                                        @endforeach
                                    </div>
                                </div>
                                <div class="d-flex align-items-start mb-2">
                                    <span class="btn btn-main cursor-normal ms-2 pr">النوع</span>
                                    <div class="ch">
                                        @foreach ($season->types as $season_type)
                                            <a href="{{url('/genre/' . $season_type->name)}}" class="btn btn-gray mb-2 ms-2">
                                                {{ $season_type->name }}
                                            </a>
                                        @endforeach
                                    </div>
                                </div>
                            </div>
                            <div class="col-12 col-md-5">
                                @if ($season->year)
                                    <div class="d-flex align-items-start mb-2">
                                        <span class="btn btn-main cursor-normal ms-2 pr">السنة</span>
                                        <div class="ch">
                                            <a href="{{url('/release-year/' . $season->year)}}" class="btn btn-gray mb-2">
                                                {{ $season->year }}
                                            </a>
                                        </div>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                    <div class="actions col-12 col-lg-3">
                        <a href="{{url('/season/' . $season->slug . '/episodes')}}" class="btn watch">
                            <i class="fa-solid fa-circle-play"></i>
                            <span style="font-size: 18px;">عرض جميع الحلقات</span>
                        </a>
                    </div>
                </div>
                <div class="stuff row">
                    @if (count($season->actors) > 0)
                        <div class="col-12 col-lg-4">
                            <div class="bg-main item">
                                <i class="fa-solid fa-people-group"></i>
                                الممثلين
                            </div>
                            <div class="items-container">
                                @foreach ($season->actors as $person)
                                    <a href="{{url('/actor/' . $person->name)}}" class="item">
                                        <img src="{{asset('/photos/imgs/avatar.png')}}" alt=""/>
                                        {{ $person->name }}
                                    </a>
                                @endforeach
                            </div>
                        </div>
                    @endif

                    @if (count($season->writers) > 0)
                        <div class="col-12 col-lg-4">
                            <div class="bg-main item">
                                <i class="fa-solid fa-person-burst"></i>
                                تأليف
                            </div>
                            <div class="items-container">
                                @foreach ($season->writers as $person)
                                    <a href="{{url('/writer/' . $person->name)}}" class="item">
                                        <img src="{{asset('/photos/imgs/avatar.png')}}" alt=""/>
                                        {{ $person->name }}
                                    </a>
                                @endforeach
                            </div>
                        </div>
                    @endif

                    @if (count($season->directors) > 0)
                        <div class="col-12 col-lg-4">
                            <div class="bg-main item">
                                <i class="fa-solid fa-person-dots-from-line"></i>
                                إخراج
                            </div>
                            <div class="items-container">
                                @foreach ($season->directors as $person)
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

                    @foreach ($season->serie->seasons as $s_season)
                        <a href="{{'/season/' . $s_season->slug}}"
                           class="epss @if ($season->id == $s_season->id) active @endif">
                            <h3 class="bg-black bg-opacity-50 text-center text-white fw-bold rounded">
                                <span class="fs-6 px-1">الموسم</span><span class="fs-2">{{ $s_season->num }}</span>
                            </h3>
                        </a>
                    @endforeach

                </div>
            </div>

            <div class="w-100 bg-main rounded my-4">
                <div
                    class="title px-3 py-2 fs-6 fw-bold d-flex align-items-center border-black border-opacity-25 border-0 border-bottom">
                    <i class="fa-solid fa-list-ul fa-flip-horizontal ms-2"></i>
                    جميع الحلقات
                </div>
                <div class="items d-flex flex-wrap px-3 py-2 gap-2">
                    @foreach ($season->episodes as $ep)
                        <a href="{{url('/episode/' . $ep->slug)}}" class="epss">
                            <h3 class="bg-black bg-opacity-50 text-center text-white fw-bold rounded">
                                <span class="fs-6 px-1">الحلقة</span><span class="fs-2">{{ $ep->num }}</span>
                            </h3>
                        </a>
                    @endforeach
                </div>
            </div>

        </div>
    </div>
@endsection
