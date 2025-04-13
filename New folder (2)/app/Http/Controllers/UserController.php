<?php

namespace App\Http\Controllers;

use App\Models\Bar;
use App\Models\Category;
use App\Models\DownServer;
use App\Models\GlidePost;
use App\Models\Post;
use App\Models\Quality;
use App\Models\Season;
use App\Models\Serie;
use App\Models\Settings;
use App\Models\Settings_scripts;
use App\Models\SocialMedia;
use App\Models\Type;
use App\Models\WatchServer;
use Illuminate\Http\Request;
use Illuminate\View\View;

class UserController extends Controller
{
    public function index(Request $request): View
    {
        $currentPage = $request->input('page', 1);
        $order = $request->input('order', 'default');

        $category = $request->input('category');
        $type = $request->input('genre');
        $quality = $request->input('quality');
        $year = $request->input('year');

        $settings = Settings::find(1);
        $settings_scripts = Settings_scripts::find(1);

        $offset = ($currentPage - 1) * ($settings->per_page ?? 50);

        $posts = Post::query()->where('show', 1);

        if ($category) $posts = $posts->whereHas('categories', function ($query) use ($category) {
            $query->where('slug', $category);
        });
        if ($type) $posts = $posts->whereHas('types', function ($query) use ($type) {
            $query->where('name', $type);
        });
        if ($quality) $posts = $posts->whereHas('qualities', function ($query) use ($quality) {
            $query->where('name', $quality);
        });
        if ($year) $posts = $posts->where('year', $year);

        if ($order == 'rating') $posts = $posts->orderBy('rating', 'desc');
        else if ($order == 'views') $posts = $posts->orderBy('views', 'desc');
        else if ($order == 'pin_index') $posts = $posts->whereNotNull('pin_index')
            ->orderBy('pin_index', 'desc')
            ->orderBy('created_at', 'desc');
        else if ($order == 'last_films') $posts = $posts->where('opt', 1)->orderBy('created_at', 'desc');
        else if ($order == 'last_eps') $posts = $posts->where('opt', 2)->orderBy('created_at', 'desc');
        else $posts = $posts->orderBy('pin_index', 'desc')->orderBy('created_at', 'desc');

        $totalPosts = $posts->count();
        $lastPage = ceil($totalPosts / $settings->per_page);
        $pagination = ['currentPage' => $currentPage, 'lastPage' => $lastPage];

        $posts = $posts->skip($offset)->take($settings->per_page)->get();

        $glide_posts = GlidePost::with('post')->orderBy('id', 'desc')->get();

        $social_media = SocialMedia::all();
        $header_bar = Bar::find(1);
        if ($header_bar) $header_bar->setHeaderItems();

        $categories = Category::all();
        $types = Type::all();
        $qualities = Quality::all();
        $years = Post::whereNotNull('year')->groupBy('year')->orderBy('year', 'desc')->pluck('year');

        return view('pages/user/index', compact('settings', 'header_bar', 'glide_posts',
            'social_media', 'posts', 'settings_scripts', 'pagination', 'categories', 'types', 'qualities', 'years'));
    }

    public function film(Request $request): View
    {
        $settings = Settings::find(1);
        $settings_scripts = Settings_scripts::find(1);

        $post = Post::where('slug', $request->slug)
            ->where('show', 1)
            ->where('opt', 1)
            ->with('categories')
            ->with('types')
            ->with('qualities')
            ->with('actors')
            ->with('directors')
            ->with('writers')
            ->firstOrFail();

        if ($post->categories->isEmpty()) {
            $similar = Post::inRandomOrder()->take(10)->get();
        } else {
            $firstCategory = $post->categories->first();
            $similar = Post::whereHas('categories', function ($query) use ($firstCategory) {
                $query->where('categories.id', $firstCategory->id);
            })->where('id', '<>', $post->id)->inRandomOrder()->take(10)->get();
        }

        $social_media = SocialMedia::all();
        $header_bar = Bar::find(1);
        if ($header_bar) $header_bar->setHeaderItems();

        return view('pages/user/details', compact('settings', 'header_bar',
            'social_media', 'post', 'similar', 'settings_scripts'));
    }

    public function episode(Request $request): View
    {
//        dd(urldecode($request->slug));
        $settings = Settings::find(1);
        $settings_scripts = Settings_scripts::find(1);

        $post = Post::where('slug', $request->slug)
            ->where('show', 1)
            ->where('opt', 2)
            ->with('categories')
            ->with('types')
            ->with('qualities')
            ->with('actors')
            ->with('directors')
            ->with('writers')
            ->with('season')
            ->firstOrFail();

        if ($post->categories->isEmpty()) {
            $similar = Post::inRandomOrder()->take(10)->get();
        } else {
            $firstCategory = $post->categories->first();
            $similar = Post::whereHas('categories', function ($query) use ($firstCategory) {
                $query->where('categories.id', $firstCategory->id);
            })->where('id', '<>', $post->id)->inRandomOrder()->take(10)->get();
        }

        $social_media = SocialMedia::all();
        $header_bar = Bar::find(1);
        if ($header_bar) $header_bar->setHeaderItems();

        return view('pages/user/details', compact('settings', 'header_bar',
            'social_media', 'post', 'similar', 'settings_scripts'));
    }

    public function post(Request $request): View
    {
        $settings = Settings::find(1);
        $settings_scripts = Settings_scripts::find(1);

        $post = Post::where('slug', $request->slug)
            ->where('show', 1)
            ->where('opt', 3)
            ->with('categories')
            ->with('types')
            ->with('qualities')
            ->with('actors')
            ->with('directors')
            ->with('writers')
            ->firstOrFail();

        if ($post->categories->isEmpty()) {
            $similar = Post::inRandomOrder()->take(10)->get();
        } else {
            $firstCategory = $post->categories->first();
            $similar = Post::whereHas('categories', function ($query) use ($firstCategory) {
                $query->where('categories.id', $firstCategory->id);
            })->where('id', '<>', $post->id)->inRandomOrder()->take(10)->get();
        }

        $social_media = SocialMedia::all();
        $header_bar = Bar::find(1);
        if ($header_bar) $header_bar->setHeaderItems();

        return view('pages/user/details', compact('settings', 'header_bar',
            'social_media', 'post', 'similar', 'settings_scripts'));
    }

    public function matchPattern($string, $pattern)
    {
        $variableNames = [];
        $pattern = preg_replace_callback('/\{(\w+)\}/', function ($matches) use (&$variableNames) {
            $variableNames[] = $matches[1];
            return '(\w+)'; // Updated the regular expression to capture alphanumeric characters
        }, $pattern);

        $pattern = '#^' . str_replace(['_', '&'], ['\_', '\&'], $pattern) . '$#';

        preg_match($pattern, $string, $matches);

        if (count($matches) > 0) {
            $variableValues = array_slice($matches, 1);

            $result = [];
            foreach ($variableNames as $index => $name) {
                $result[$name] = $variableValues[$index];
            }

            return $result;
        }

        return false;
    }

    public function watch(Request $request): View
    {
        $settings = Settings::find(1);
        $settings_scripts = Settings_scripts::find(1);
        $social_media = SocialMedia::all();
        $header_bar = Bar::find(1);
        if ($header_bar) $header_bar->setHeaderItems();

        $query_post = Post::query()->where('show', 1);
        $query_post = $query_post->where('slug', $request->slug)->with('categories');
        $query_post = $query_post->firstOrFail();

        $query_post->increment('views');

        if ($query_post->opt == 2) {
            $post = Post::where('id', $query_post->id)->with('season')->firstOrFail();
        } else $post = $query_post;

        $servers = WatchServer::query()->orderBy('rank', 'asc')->orderBy('id', 'desc')->get();
        $watchServers = [];
        foreach ($post->watch_servers as $server) {
            $matchingServer = $servers->first(function ($item) use ($server) {
                return $item->type == 1 &&
                    (str_contains($server, str_replace('https://', '', $item->remove))
                        || str_contains($server, str_replace('http://', '', $item->remove))
                        || str_contains($server, str_replace('https://', '', $item->add))
                        || str_contains($server, str_replace('http://', '', $item->add)));
            });

            if ($matchingServer) {
                $rm = str_replace('https://', '', $matchingServer->remove);
                $rm = str_replace('http://', '', $rm);
                $add = str_replace('https://', '', $matchingServer->add);
                $add = str_replace('http://', '', $add);

                $server = str_replace('http://', 'https://', $server);

                $newServer = [
                    'id' => $matchingServer->id,
                    'name' => $matchingServer->name,
                    'img' => $matchingServer->img,
                    'url' => str_replace($rm, $add, $server),
                    'rank' => $matchingServer->rank,
                ];
                $watchServers[] = $newServer;
            } else {
                $patterns = $servers->where('type', 2);


                foreach ($patterns as $pattern) {
                    $variableValues = $this->matchPattern($server, $pattern->remove);
                    if ($variableValues !== false) {
                        $new_server = $pattern->add;
                        foreach ($variableValues as $name => $value) {
                            $new_server = str_replace('{' . $name . '}', $value, $new_server);
                        }
                        $newServer = [
                            'id' => $pattern->id,
                            'name' => $pattern->name,
                            'img' => $pattern->img,
                            'url' => $new_server,
                            'rank' => $pattern->rank,
                        ];
                        $watchServers[] = $newServer;
                        break;
                    }
                }
            }

        }

        usort($watchServers, function ($a, $b) {
            if ($a['rank'] == $b['rank']) {
                // If rank is the same, order by id in descending order
                return ($a['id'] < $b['id']) ? 1 : -1;
            }
            // Order by rank in ascending order
            return ($a['rank'] > $b['rank']) ? 1 : -1;
        });
        $post->watch_servers = $watchServers;

        return view('pages/user/watch', compact('settings', 'settings_scripts', 'social_media', 'header_bar', 'post'));
    }

    public function download(Request $request): View
    {
        $settings = Settings::find(1);
        $settings_scripts = Settings_scripts::find(1);
        $social_media = SocialMedia::all();
        $header_bar = Bar::find(1);
        if ($header_bar) $header_bar->setHeaderItems();

        $query_post = Post::query()->where('show', 1);
        $query_post = $query_post->where('slug', $request->slug)->with('categories');
        $query_post = $query_post->firstOrFail();

        if ($query_post->opt == 2) {
            $post = Post::where('id', $query_post->id)->with('season')->firstOrFail();
        } else $post = $query_post;

        $servers = DownServer::query()->orderBy('rank', 'asc')->orderBy('id', 'desc')->get();
        $downServers = [];
        foreach ($post->down_servers as $server) {
            $matchingServer = $servers->first(function ($item) use ($server) {
                return str_contains($server['code'], str_replace('https://', '', $item->remove))
                    || str_contains($server['code'], str_replace('http://', '', $item->remove))
                    || str_contains($server['code'], str_replace('https://', '', $item->add))
                    || str_contains($server['code'], str_replace('http://', '', $item->add));
            });

            if (!$matchingServer) {
                continue;
            }

            $rm = str_replace('https://', '', $matchingServer->remove);
            $rm = str_replace('http://', '', $rm);
            $add = str_replace('https://', '', $matchingServer->add);
            $add = str_replace('http://', '', $add);

            $newServer = [
                'name' => $matchingServer->name,
                'size' => $server['size'],
                'code' => str_replace($rm, $add, $server['code']),
                'rank' => $matchingServer->rank,
            ];
            $downServers[] = $newServer;
        }

        usort($downServers, function ($a, $b) {
            if ($a['rank'] == $b['rank']) {
                return 0;
            }
            return ($a['rank'] > $b['rank']) ? 1 : -1;
        });

        function orderServers($server_list)
        {
            $serversBySize = [];

            foreach ($server_list as $item) {
                $size = $item['size'];
                if (isset($serversBySize[$size])) {
                    $serversBySize[$size][] = $item;
                } else {
                    $serversBySize[$size] = [$item];
                }
            }

            uksort($serversBySize, function ($sizeA, $sizeB) {
                return $sizeB - $sizeA;
            });

            $orderedServers = [];
            foreach ($serversBySize as $size => $servers) {
                $orderedServers[] = [
                    'size' => $size,
                    'servers' => $servers
                ];
            }

            return $orderedServers;
        }

        $post->down_servers = orderServers($downServers);

        return view('pages/user/download', compact('settings', 'settings_scripts', 'social_media', 'header_bar', 'post'));
    }

    public function season(Request $request): View
    {
        $settings = Settings::find(1);
        $settings_scripts = Settings_scripts::find(1);
        $social_media = SocialMedia::all();
        $header_bar = Bar::find(1);
        if ($header_bar) $header_bar->setHeaderItems();

        $season = Season::where('slug', $request->slug)
            ->with('types')
            ->with('categories')
            ->with('qualities')
            ->with('actors')
            ->with('directors')
            ->with('writers')
            ->with('episodes')
            ->with('serie')
            ->firstOrFail();

        return view('pages/user/season', compact('settings', 'settings_scripts', 'social_media', 'header_bar', 'season'));
    }

    public function season_episodes(Request $request): View
    {
        $settings = Settings::find(1);
        $settings_scripts = Settings_scripts::find(1);
        $social_media = SocialMedia::all();
        $header_bar = Bar::find(1);
        if ($header_bar) $header_bar->setHeaderItems();

        $season = Season::where('slug', $request->slug)
            ->with(['episodes' => function ($query) {
                $query->orderBy('num', 'desc');
            }])
            ->firstOrFail();
        $parent_tp = 'season';

        return view('pages/user/children', compact('settings', 'settings_scripts', 'social_media', 'header_bar', 'season', 'parent_tp'));
    }

    public function serie(Request $request): View
    {
        $settings = Settings::find(1);
        $settings_scripts = Settings_scripts::find(1);
        $social_media = SocialMedia::all();
        $header_bar = Bar::find(1);
        if ($header_bar) $header_bar->setHeaderItems();

        $serie = Serie::where('slug', $request->slug)
            ->with('types')
            ->with('categories')
            ->with('qualities')
            ->with('actors')
            ->with('directors')
            ->with('writers')
            ->with('seasons')
            ->firstOrFail();

        return view('pages/user/serie', compact('settings', 'settings_scripts', 'social_media', 'header_bar', 'serie'));
    }

    public function serie_seasons(Request $request): View
    {
        $settings = Settings::find(1);
        $settings_scripts = Settings_scripts::find(1);
        $social_media = SocialMedia::all();
        $header_bar = Bar::find(1);
        if ($header_bar) $header_bar->setHeaderItems();

        $serie = Serie::where('slug', $request->slug)
            ->with(['seasons' => function ($query) {
                $query->orderBy('num', 'desc');
            }])
            ->firstOrFail();
        $parent_tp = 'serie';

        return view('pages/user/children', compact('settings', 'settings_scripts', 'social_media', 'header_bar', 'serie', 'parent_tp'));
    }

    public function category(Request $request): View
    {
        $category = $request->slug;

        $type = $request->input('genre');
        $quality = $request->input('quality');
        $year = $request->input('year');

        $settings = Settings::find(1);
        $settings_scripts = Settings_scripts::find(1);
        $social_media = SocialMedia::all();
        $header_bar = Bar::find(1);
        if ($header_bar) $header_bar->setHeaderItems();

        $posts = Post::query()
            ->where('show', 1)
            ->whereHas('categories', function ($query) use ($category) {
                $query->where('slug', $category);
            });

        if ($type) $posts = $posts->whereHas('types', function ($query) use ($type) {
            $query->where('name', $type);
        });

        if ($quality) $posts = $posts->whereHas('qualities', function ($query) use ($quality) {
            $query->where('name', $quality);
        });

        if ($year) $posts = $posts->where('year', $year);


        $currentPage = $request->input('page', 1);
        $offset = ($currentPage - 1) * ($settings->per_page ?? 50);

        $total = $posts->count();
        $lastPage = ceil($total / $settings->per_page);
        $pagination = ['currentPage' => $currentPage, 'lastPage' => $lastPage];

        $posts = $posts->orderBy('created_at', 'desc')
            ->with('types')
            ->with('categories')
            ->with('qualities')
            ->with('actors')
            ->with('directors')
            ->with('writers')
            ->skip($offset)
            ->take($settings->per_page)
            ->get();

        $categories = Category::all();
        $types = Type::all();
        $qualities = Quality::all();
        $years = Post::whereNotNull('year')->groupBy('year')->orderBy('year', 'desc')->pluck('year');

        $info = Category::where('slug', $category)->firstOrFail();

        return view('pages/user/options', compact('settings', 'settings_scripts', 'social_media', 'header_bar', 'posts',
            'pagination', 'categories', 'types', 'qualities', 'years', 'info'));
    }

    public function genre(Request $request): View
    {
        $type = $request->slug;

        $category = $request->input('category');
        $quality = $request->input('quality');
        $year = $request->input('year');

        $settings = Settings::find(1);
        $settings_scripts = Settings_scripts::find(1);
        $social_media = SocialMedia::all();
        $header_bar = Bar::find(1);
        if ($header_bar) $header_bar->setHeaderItems();

        $posts = Post::query()
            ->where('show', 1)
            ->whereHas('types', function ($query) use ($type) {
                $query->where('name', $type);
            });

        if ($category) $posts = $posts->whereHas('categories', function ($query) use ($category) {
            $query->where('slug', $category);
        });

        if ($quality) $posts = $posts->whereHas('qualities', function ($query) use ($quality) {
            $query->where('name', $quality);
        });

        if ($year) $posts = $posts->where('year', $year);


        $currentPage = $request->input('page', 1);
        $offset = ($currentPage - 1) * ($settings->per_page ?? 50);

        $total = $posts->count();
        $lastPage = ceil($total / $settings->per_page);
        $pagination = ['currentPage' => $currentPage, 'lastPage' => $lastPage];

        $posts = $posts->orderBy('created_at', 'desc')
            ->with('types')
            ->with('categories')
            ->with('qualities')
            ->with('actors')
            ->with('directors')
            ->with('writers')
            ->skip($offset)
            ->take($settings->per_page)
            ->get();

        $categories = Category::all();
        $types = Type::all();
        $qualities = Quality::all();
        $years = Post::whereNotNull('year')->groupBy('year')->orderBy('year', 'desc')->pluck('year');

        return view('pages/user/options', compact('settings', 'settings_scripts', 'social_media', 'header_bar', 'posts',
            'pagination', 'categories', 'types', 'qualities', 'years'));
    }

    public function year(Request $request): View
    {
        $year = $request->slug;

        $category = $request->input('category');
        $quality = $request->input('quality');
        $type = $request->input('genre');

        $settings = Settings::find(1);
        $settings_scripts = Settings_scripts::find(1);
        $social_media = SocialMedia::all();
        $header_bar = Bar::find(1);
        if ($header_bar) $header_bar->setHeaderItems();

        $posts = Post::query()
            ->where('show', 1)
            ->where('year', $year);

        if ($category) $posts = $posts->whereHas('categories', function ($query) use ($category) {
            $query->where('slug', $category);
        });

        if ($quality) $posts = $posts->whereHas('qualities', function ($query) use ($quality) {
            $query->where('name', $quality);
        });

        if ($type) $posts = $posts->whereHas('types', function ($query) use ($type) {
            $query->where('name', $type);
        });

        $currentPage = $request->input('page', 1);
        $offset = ($currentPage - 1) * ($settings->per_page ?? 50);

        $total = $posts->count();
        $lastPage = ceil($total / $settings->per_page);
        $pagination = ['currentPage' => $currentPage, 'lastPage' => $lastPage];

        $posts = $posts->orderBy('created_at', 'desc')
            ->with('types')
            ->with('categories')
            ->with('qualities')
            ->with('actors')
            ->with('directors')
            ->with('writers')
            ->skip($offset)
            ->take($settings->per_page)
            ->get();

        $categories = Category::all();
        $types = Type::all();
        $qualities = Quality::all();
        $years = Post::whereNotNull('year')->groupBy('year')->orderBy('year', 'desc')->pluck('year');

        return view('pages/user/options', compact('settings', 'settings_scripts', 'social_media', 'header_bar', 'posts',
            'pagination', 'categories', 'types', 'qualities', 'years'));
    }

    public function quality(Request $request): View
    {
        $quality = $request->slug;

        $category = $request->input('category');
        $year = $request->input('year');
        $type = $request->input('genre');

        $settings = Settings::find(1);
        $settings_scripts = Settings_scripts::find(1);
        $social_media = SocialMedia::all();
        $header_bar = Bar::find(1);
        if ($header_bar) $header_bar->setHeaderItems();

        $posts = Post::query()
            ->where('show', 1)
            ->whereHas('qualities', function ($query) use ($quality) {
                $query->where('name', $quality);
            });

        if ($category) $posts = $posts->whereHas('categories', function ($query) use ($category) {
            $query->where('slug', $category);
        });

        if ($type) $posts = $posts->whereHas('types', function ($query) use ($type) {
            $query->where('name', $type);
        });

        if ($year) $posts = $posts->where('year', $year);

        $currentPage = $request->input('page', 1);
        $offset = ($currentPage - 1) * ($settings->per_page ?? 50);

        $total = $posts->count();
        $lastPage = ceil($total / $settings->per_page);
        $pagination = ['currentPage' => $currentPage, 'lastPage' => $lastPage];

        $posts = $posts->orderBy('created_at', 'desc')
            ->with('types')
            ->with('categories')
            ->with('qualities')
            ->with('actors')
            ->with('directors')
            ->with('writers')
            ->skip($offset)
            ->take($settings->per_page)
            ->get();

        $categories = Category::all();
        $types = Type::all();
        $qualities = Quality::all();
        $years = Post::whereNotNull('year')->groupBy('year')->orderBy('year', 'desc')->pluck('year');

        return view('pages/user/options', compact('settings', 'settings_scripts', 'social_media', 'header_bar', 'posts',
            'pagination', 'categories', 'types', 'qualities', 'years'));
    }

    public function search(Request $request): View
    {
        $search = $request->input('s');

        $currentPage = $request->input('page', 1);

        $settings = Settings::find(1);
        $settings_scripts = Settings_scripts::find(1);
        $social_media = SocialMedia::all();
        $header_bar = Bar::find(1);
        if ($header_bar) $header_bar->setHeaderItems();

        $offset = ($currentPage - 1) * ($settings->per_page ?? 50);
        $posts = Post::query()
            ->where('show', 1)
            ->where('title', 'like', '%' . $search . '%');

        $totalPosts = $posts->count();
        $lastPage = ceil($totalPosts / $settings->per_page);
        $pagination = ['currentPage' => $currentPage, 'lastPage' => $lastPage];

        $posts = $posts->orderBy('created_at', 'desc')
            ->with('categories')
            ->skip($offset)
            ->take($settings->per_page)
            ->get();

        return view('pages/user/search', compact('settings', 'settings_scripts', 'social_media', 'header_bar', 'posts', 'pagination', 'search'));
    }

    public function actor(Request $request): View
    {
        $name = $request->slug;

        $posts = Post::query()
            ->where('show', 1)
            ->whereHas('actors', function ($query) use ($name) {
                $query->where('name', $name);
            });

        $settings = Settings::find(1);
        $settings_scripts = Settings_scripts::find(1);
        $social_media = SocialMedia::all();
        $header_bar = Bar::find(1);
        if ($header_bar) $header_bar->setHeaderItems();

        $currentPage = $request->input('page', 1);
        $offset = ($currentPage - 1) * ($settings->per_page ?? 50);

        $total = $posts->count();
        $lastPage = ceil($total / $settings->per_page);
        $pagination = ['currentPage' => $currentPage, 'lastPage' => $lastPage];

        $posts = $posts->orderBy('created_at', 'desc')
            ->with('categories')
            ->skip($offset)
            ->take($settings->per_page)
            ->get();

        return view('pages/user/persons', compact('settings', 'settings_scripts', 'social_media', 'header_bar', 'posts', 'pagination'));
    }

    public function director(Request $request): View
    {
        $name = $request->slug;

        $posts = Post::query()
            ->where('show', 1)
            ->whereHas('directors', function ($query) use ($name) {
                $query->where('name', $name);
            });

        $settings = Settings::find(1);
        $settings_scripts = Settings_scripts::find(1);
        $social_media = SocialMedia::all();
        $header_bar = Bar::find(1);
        if ($header_bar) $header_bar->setHeaderItems();

        $currentPage = $request->input('page', 1);
        $offset = ($currentPage - 1) * ($settings->per_page ?? 50);

        $total = $posts->count();
        $lastPage = ceil($total / $settings->per_page);
        $pagination = ['currentPage' => $currentPage, 'lastPage' => $lastPage];

        $posts = $posts->orderBy('created_at', 'desc')
            ->with('categories')
            ->skip($offset)
            ->take($settings->per_page)
            ->get();

        return view('pages/user/persons', compact('settings', 'settings_scripts', 'social_media', 'header_bar', 'posts', 'pagination'));
    }

    public function writer(Request $request): View
    {
        $name = $request->slug;

        $posts = Post::query()
            ->where('show', 1)
            ->whereHas('writers', function ($query) use ($name) {
                $query->where('name', $name);
            });

        $settings = Settings::find(1);
        $settings_scripts = Settings_scripts::find(1);
        $social_media = SocialMedia::all();
        $header_bar = Bar::find(1);
        if ($header_bar) $header_bar->setHeaderItems();

        $currentPage = $request->input('page', 1);
        $offset = ($currentPage - 1) * ($settings->per_page ?? 50);

        $total = $posts->count();
        $lastPage = ceil($total / $settings->per_page);
        $pagination = ['currentPage' => $currentPage, 'lastPage' => $lastPage];

        $posts = $posts->orderBy('created_at', 'desc')
            ->with('categories')
            ->skip($offset)
            ->take($settings->per_page)
            ->get();

        return view('pages/user/persons', compact('settings', 'settings_scripts', 'social_media', 'header_bar', 'posts', 'pagination'));
    }
}
