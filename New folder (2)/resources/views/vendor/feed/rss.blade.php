<?= '<?xml version="1.0" encoding="UTF-8"?>'.PHP_EOL ?>
<rss version="2.0" xmlns:atom="http://www.w3.org/2005/Atom">
    <channel>
        <atom:link href="{{ url($meta['link']) }}" rel="self" type="application/rss+xml" />
        <title>{{ $meta['title'] }}</title>
        <link>{{ url($meta['link']) }}</link>

        @if(!empty($meta['image']))
        <image>
            <url>{{ $meta['image'] }}</url>
            <title>{{ $meta['title'] }}</title>
            <link>{{ url($meta['link']) }}</link>
        </image>
        @endif

        <description>{{ $meta['description'] }}</description>
        <language>{{ $meta['language'] }}</language>
        <pubDate>{{ $meta['updated'] }}</pubDate>

        @foreach($items as $item)
            <item>
                <title>{{ $item->title }}</title>
                <link>{{ rawurldecode(url($item->link)) }}</link>
                <description>{{ $item->summary }}</description>
                <author>{{ $item->authorName }}</author>
                <guid>{{ url($item->id) }}</guid>
                <pubDate>{{ $item->timestamp() }}</pubDate>
                
                @foreach($item->category as $category)
                    <category>{{ $category }}</category>
                @endforeach
            </item>
        @endforeach
    </channel>
</rss>
