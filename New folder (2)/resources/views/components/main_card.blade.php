@php
    if (isset($type) && $type == 'season') {
        $link = '/season/' . $post->slug;
    } else {
        if ($post->opt == 1) {
            $link = '/film/' . $post->slug;
        } elseif ($post->opt == 3) {
            $link = '/post/' . $post->slug;
        } else {
            $link = '/episode/' . $post->slug;
        }
    }
@endphp

<a href="{{ url($link) }}" class="show-card"
   style="background-image: url({{$post->img ? asset($post->img) : asset('/photos/imgs/placeholder.webp')}}); --br: {{ $br ?? '' }};">
    <div class="card-overlay" style="--br: {{ $br ?? '' }}"></div>
    <div class="card-content">
        <div class="d-flex justify-content-between align-items-start">
            <div class="d-flex flex-column"
                 style="position: relative; right: 0.6rem; top: 0.6rem; {{ ($post->opt != 2) ? 'opacity: 0;' : '' }}">
                <span class="ep">الحلقة<br/>{{ $post->num }}</span>
            </div>
            <div class="d-flex flex-column align-items-end" style="position: relative; left: 0.6rem; top: 0.6rem;">
                @if ($post->categories->count() > 0)
                    <span class="categ">{{ $post->categories[0]->name }}</span>
                @endif
                @if ($post->rating)
                    <span class="rate">{{ $post->rating }}
                        <i class="fa-solid fa-star"></i>
                    </span>
                @endif
            </div>
        </div>
        <span class="playicon"><i class="fa-solid fa-circle-play"></i></span>
        <div class="">
            <h4 class="title">{{ $post->title }}</h4>
            <h5 class="description">
                @if ($post->story)
                    {!! $post->story !!}
                @endif
            </h5>
        </div>
    </div>

    @if($post->sticker_text)
        <span class="sticker" style="background: {{$post->sticker_color}}">{{$post->sticker_text}}</span>
    @endif
</a>
