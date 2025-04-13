@extends('layouts/main_layout')

@if($parent_tp == 'season')
    @php $sub_title = 'جميع حلقات ' . $season->title; @endphp
@elseif($parent_tp == 'serie')
    @php $sub_title = 'جميع مواسم ' . $serie->title; @endphp
@endif

@section('title', $sub_title . ' - ' . $settings->site_name)

@section('remove_meta', true)

@section('head')
    <style>
        #title {
            background: var(--color-main);
            width: max-content;
            padding-inline: 10px 25px;
            border-radius: 50rem 0 0 50rem;
            padding-block: 10px;
            color: white;
            font-size: 16px;
            font-weight: 600;
            margin: 25px 10px 0px 0;
        }
    </style>
@endsection

@section('content')
    <div class="container">
        <div>
            <div id="title">{{$sub_title}}</div>
        </div>


        <div class="container my-3">
            <div class="shows-container row">
                @if($parent_tp == 'season')
                    @foreach($season->episodes as $child)
                        <div class="col-6 col-md-4 col-lg-20ps mb-3">
                            @include('components.main_card', ['post' => $child,'br' => '10px'])
                        </div>
                    @endforeach
                @elseif($parent_tp == 'serie')
                    @foreach($serie->seasons as $child)
                        <div class="col-6 col-md-4 col-lg-20ps mb-3">
                            @include('components.main_card', ['post' => $child,'br' => '10px', 'type' => 'season'])
                        </div>
                    @endforeach
                @endif
            </div>
        </div>
    </div>
@endsection
