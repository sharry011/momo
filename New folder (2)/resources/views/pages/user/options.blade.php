@extends('layouts/main_layout')

@php $sub_title = str_replace('-', ' ', request()->slug); @endphp

@section('title', $sub_title . ' - ' . $settings->site_name)

@if(!request()->is('category/*'))
    @section('remove_meta', true)
@else
    @section('remove_meta', false)
    @section('page_keywords', $info->desc)
    @section('page_description', html_entity_decode($info->desc))
    @section('page_type', 'website')
    @section('page_img', asset('/favicon.ico'))
@endif

@section('head')
    <style>
        .logo-section {
            box-shadow: none !important;
        }
    </style>
@endsection

@section('content')

    <div id="filtter" style="background-color: var(--color-gray1);">
        <div class="adv-filter container pt-2">
            <div class="py-4 px-3 w-100 bg-gray1 rounded">
                <div class="row justify-content-between">
                    <div class="col-12 col-md-3 d-flex align-items-center mb-2">
                        <i class="fa-solid fa-clapperboard text-white fs-4 ms-3"></i>
                        <p class="text-white fw-bold fs-5 m-0">
                            {{ $sub_title }}
                        </p>
                    </div>
                    <div class="col-12 col-md-9 col-lg-9">
                        <div class="row">
                            @if (!request()->is('category/*'))
                                <div class="col-12 col-md-4 my-2">
                                    <select class="form-select ci w-100" aria-label="Default select example"
                                            onchange="updateQuery('category', this.value)">
                                        <option value="">كل الأقسام</option>
                                        @foreach ($categories as $item)
                                            <option value="{{ $item->slug }}"
                                                    @if (request()->query('category') == $item->slug) selected @endif>
                                                {{ $item->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            @endif

                            @if (!request()->is('genre/*'))
                                <div class="col-12 col-md-4 my-2">
                                    <select class="form-select ci w-100" aria-label="Default select example"
                                            onchange="updateQuery('genre', this.value)">
                                        <option value="">كل الأنواع</option>
                                        @foreach ($types as $item)
                                            <option value="{{ $item->name }}"
                                                    @if (request()->query('genre') == $item->name) selected @endif>
                                                {{ $item->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            @endif

                            @if (!request()->is('release-year/*'))
                                <div class="col-12 col-md-4 my-2">
                                    <select class="form-select ci w-100" aria-label="Default select example"
                                            onchange="updateQuery('year', this.value)">
                                        <option value="">كل السنوات</option>
                                        @foreach ($years as $item)
                                            <option value="{{ $item }}"
                                                    @if (request()->query('year') == $item) selected @endif>
                                                {{ $item }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            @endif

                            @if (!request()->is('quality/*'))
                                <div class="col-12 col-md-4 my-2">
                                    <select class="form-select ci w-100" aria-label="Default select example"
                                            onchange="updateQuery('quality', this.value)">
                                        <option value="">كل الجودات</option>
                                        @foreach ($qualities as $item)
                                            <option value="{{ $item->name }}"
                                                    @if (request()->query('quality') == $item->name) selected @endif>
                                                {{ $item->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="container">
        <div class="container my-3">
            <div class="shows-container row">
                @foreach($posts as $post)
                    <div class="col-6 col-md-4 col-lg-20ps mb-3">
                        @include('components.main_card', ['post' => $post,'br' => '10px'])
                    </div>
                @endforeach
            </div>
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
    </script>
@endsection
