@extends('layouts/main_layout')


@php $sub_title = 'نتائج البحث عن : ' . $search; @endphp

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
