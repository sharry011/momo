<?php

namespace App\Http\Controllers;

use App\Models\Bar;
use App\Models\Category;
use App\Models\DownServer;
use App\Models\GlidePost;
use App\Models\Person;
use App\Models\Post;
use App\Models\Quality;
use App\Models\Season;
use App\Models\Serie;
use App\Models\Settings;
use App\Models\Settings_scripts;
use App\Models\SocialMedia;
use App\Models\Type;
use App\Models\User;
use App\Models\WatchServer;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Hash;
use Illuminate\View\View;
use Mockery\Exception;
use SimpleXMLElement;

class AdminController extends Controller
{
    protected $specialChars = ['!', '@', '#', '$', '%', '^', '&', '*'];

    public function articles_all(Request $request): View
    {
        $search = $request->input('s');
        $pinned = (bool)$request->input('pinned');
        $hidden = (bool)$request->input('hidden');

        $settings = Settings::find(1);
        $currentPage = $request->input('page', 1);
        $offset = ($currentPage - 1) * ($settings->per_page ?? 50);


        $posts = Post::query();
        if ($search) $posts = $posts->where('title', 'like', '%' . $search . '%');
        if ($pinned) $posts = $posts->whereNotNull('pin_index');
        if ($hidden) $posts = $posts->where('show', 0);

        $totalPosts = $posts->count();
        $lastPage = ceil($totalPosts / $settings->per_page);
        $pagination = ['currentPage' => $currentPage, 'lastPage' => $lastPage];

        $posts = $posts->orderBy('created_at', 'desc')
            ->with('categories')
            ->offset($offset)
            ->limit($settings->per_page ?? 50)->get();

        return view('pages/admin/articles/all', compact('posts', 'pagination'));
    }

    public function articles_new_page(): View
    {
        $categories = Category::all();
        $types = Type::all();
        $qualities = Quality::all();

        return view('pages/admin/articles/new', compact('categories', 'types', 'qualities'));
    }

    public function articles_new(Request $request)
    {
        // add post
        $post = new Post();
        $post->title = $request->input('title');

        $originalSlug = strtolower(str_replace(' ', '-', $request->input('title')));
        $originalSlug = str_replace($this->specialChars, '', $originalSlug);

        $slug = $originalSlug;
        $counter = 1;
        while (Post::where('slug', $slug)->exists()) {
            $slug = $originalSlug . '-' . $counter++;
        }
        $post->slug = $slug;
        $post->img = $request->input('img_link');
        $post->story = $request->input('story');
        $post->views = $request->input('views');
        $post->rating = $request->input('rating');
        $post->runtime = $request->input('runtime');
        $post->triller = $request->input('triller');
        $post->year = $request->input('year');
        $post->show = $request->input('show');
        $post->sticker_text = $request->input('sticker_text');
        $post->sticker_color = $request->input('sticker_color');
        if ($request->hasFile('post_img')) {
            $file = $request->file('post_img');
            $filename = time() . '_' . str_replace(['(', ')'], '_', $file->getClientOriginalName());
            $imageUrl = 'photos/uploads/' . $filename;
            $file->move(public_path('photos/uploads'), $filename);
            $post->img = '/' . $imageUrl;
        }
        if ($request->input('pin') == 'on') {
            $maxPinIndex = Post::max('pin_index') ?? 0;
            $post->pin_index = $maxPinIndex + 1;
        }

        $post->opt = $request->input('opt');
        if ($post->opt == 2) {
            $post->num = $request->input('ep_num');
            $post->season_id = $request->input('selected_season');
        }

        $watch_servers = $request->input('watch_servers') ? explode("\r\n", trim($request->input('watch_servers'))) : [];
        $down_servers_1080 = $request->input('post_down_servers_1080') ? explode("\r\n", trim($request->input('post_down_servers_1080'))) : [];
        $down_servers_720 = $request->input('post_down_servers_720') ? explode("\r\n", trim($request->input('post_down_servers_720'))) : [];
        $down_servers_480 = $request->input('post_down_servers_480') ? explode("\r\n", trim($request->input('post_down_servers_480'))) : [];
        $down_servers_360 = $request->input('post_down_servers_360') ? explode("\r\n", trim($request->input('post_down_servers_360'))) : [];
        $post_down_servers_multi = $request->input('post_down_servers_multi') ? explode("\r\n", trim($request->input('post_down_servers_multi'))) : [];

        $new_down_servers = [];
        foreach ($post_down_servers_multi as $server) {
            if (trim($server)) $new_down_servers[] = ['size' => 10000, 'code' => trim($server)];
        }
        foreach ($down_servers_1080 as $server) {
            if (trim($server)) $new_down_servers[] = ['size' => 1080, 'code' => trim($server)];
        }
        foreach ($down_servers_720 as $server) {
            if (trim($server)) $new_down_servers[] = ['size' => 720, 'code' => trim($server)];
        }
        foreach ($down_servers_480 as $server) {
            if (trim($server)) $new_down_servers[] = ['size' => 480, 'code' => trim($server)];
        }
        foreach ($down_servers_360 as $server) {
            if (trim($server)) $new_down_servers[] = ['size' => 360, 'code' => trim($server)];
        }

        $post->watch_servers = $watch_servers;
        $post->down_servers = $new_down_servers;

        $post->save();


        if (!empty($request->input('selected_categs'))) foreach ($request->input('selected_categs') as $category_name) {
            $category = Category::where('name', $category_name)->first();
            if (!$category) {
                $category = new Category();
                $category->name = $category_name;
                $category->slug = strtolower(str_replace(' ', '-', $category_name));
                $category->save();
//                $this->add_category_to_xml($category);
            }
            try {
                if (!$post->categories()->where('category_id', $category->id)->count())
                    $post->categories()->attach($category->id);
            } catch (\Exception $e) {
            }
        }
        if (!empty($request->input('selected_types'))) foreach ($request->input('selected_types') as $type_name) {
            $type = Type::where('name', $type_name)->first();
            if (!$type) {
                $type = new Type();
                $type->name = $type_name;
                $type->slug = strtolower(str_replace(' ', '-', $type_name));
                $type->save();
            }
            try {
                if (!$post->types()->where('type_id', $type->id)->count())
                    $post->types()->attach($type->id);
            } catch (\Exception $e) {
            }
        }
        if (!empty($request->input('selected_qualities'))) foreach ($request->input('selected_qualities') as $quality_name) {
            $quality = Quality::where('name', $quality_name)->first();
            if (!$quality) {
                $quality = new Quality();
                $quality->name = $quality_name;
                $quality->slug = strtolower(str_replace(' ', '-', $quality_name));
                $quality->save();
            }
            try {
                if (!$post->qualities()->where('quality_id', $quality->id)->count())
                    $post->qualities()->attach($quality->id);
            } catch (\Exception $e) {
            }
        }
        if (!empty($request->input('selected_actors'))) foreach ($request->input('selected_actors') as $actor_name) {
            $person = Person::where('name', $actor_name)->first();
            if (!$person) {
                $person = new Person();
                $person->name = $actor_name;
                $person->slug = strtolower(str_replace(' ', '-', $actor_name));
                $person->save();
            }
            try {
                if (!$post->actors()->where('person_id', $person->id)->count())
                    $post->actors()->attach($person->id);
            } catch (\Exception $e) {
            }
        }
        if (!empty($request->input('selected_directors'))) foreach ($request->input('selected_directors') as $director_name) {
            $person = Person::where('name', $director_name)->first();
            if (!$person) {
                $person = new Person();
                $person->name = $director_name;
                $person->slug = strtolower(str_replace(' ', '-', $director_name));
                $person->save();
            }
            try {
                if (!$post->directors()->where('person_id', $person->id)->count())
                    $post->directors()->attach($person->id);
            } catch (\Exception $e) {
            }
        }
        if (!empty($request->input('selected_writers'))) foreach ($request->input('selected_writers') as $writer_name) {
            $person = Person::where('name', $writer_name)->first();
            if (!$person) {
                $person = new Person();
                $person->name = $writer_name;
                $person->slug = strtolower(str_replace(' ', '-', $writer_name));
                $person->save();
            }
            try {
                if (!$post->writers()->where('person_id', $person->id)->count())
                    $post->writers()->attach($person->id);
            } catch (\Exception $e) {
            }
        }

//        $this->add_post_to_xml($post);

        return redirect()->route('admin.articles.all.get');

    }

    private function add_post_to_xml($post)
    {
        try {
            $year = now()->year;
            $month = now()->month;

            $xmlFilename = "sitemap-posts-$year-$month.xml";
            $xmlPath = public_path($xmlFilename);

            if (!File::exists($xmlPath)) {
                // Create a new XML file if it doesn't exist
                $xmlString = '<?xml version="1.0" encoding="UTF-8"?>' . PHP_EOL;
                $xmlString .= '<?xml-stylesheet href="/xml.xsl" type="text/xsl"?>' . PHP_EOL;
                $xmlString .= '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9" xmlns:xhtml="http://www.w3.org/1999/xhtml" xmlns:image="http://www.google.com/schemas/sitemap-image/1.1" xmlns:video="http://www.google.com/schemas/sitemap-video/1.1"></urlset>';

                File::put($xmlPath, $xmlString);
            }

            $xmlContent = File::get($xmlPath);

            $xml = new SimpleXMLElement($xmlContent);

            $newUrl = $xml->addChild('url');

            $loc = null;
            if ($post->opt == 1) $loc = url('/film/' . $post->slug);
            if ($post->opt == 2) $loc = url('/episode/' . $post->slug);
            if ($post->opt == 3) $loc = url('/post/' . $post->slug);
            $newUrl->addChild('loc', $loc); // Replace with your new URL
            $newUrl->addChild('priority', '1'); // Set the priority as needed
            $newUrl->addChild('lastmod', $post->created_at); // Set the last modification date
            $newUrl->addChild('changefreq', 'monthly'); // Set the change frequency

            $updatedXmlContent = $xml->asXML();

            File::put($xmlPath, $updatedXmlContent);
        } catch (\Exception $e) {
            return;
        }
    }

    private function add_category_to_xml($category)
    {
        try {
            $xmlFilename = "sitemap-category.xml";
            $xmlPath = public_path($xmlFilename);

            $xmlContent = File::get($xmlPath);

            $xml = new SimpleXMLElement($xmlContent);

            $newUrl = $xml->addChild('url');

            $newUrl->addChild('loc', url('category/' . $category->slug)); // Replace with your new URL
            $newUrl->addChild('priority', '0.8'); // Set the priority as needed
            $newUrl->addChild('lastmod', now()); // Set the last modification date
            $newUrl->addChild('changefreq', 'Hourly'); // Set the change frequency
            $updatedXmlContent = $xml->asXML();

            File::put($xmlPath, $updatedXmlContent);
        } catch (\Exception $e) {
            return;
        }
    }

    public function articles_update_page(Request $request): View
    {
        $post = Post::where('id', $request->id)
            ->with('categories')
            ->with('types')
            ->with('qualities')
            ->with('actors')
            ->with('directors')
            ->with('writers')
            ->with('season')
            ->firstorfail();

        $categories = Category::all();
        $types = Type::all();
        $qualities = Quality::all();


        return view('pages/admin/articles/update', compact('post', 'categories', 'types', 'qualities'));
    }

    public function articles_update(Request $request)
    {
        $post = Post::where('id', $request->id)->firstorfail();

        $post->title = $request->input('title');
        $post->slug = str_replace($this->specialChars, '', $request->input('slug'));
        $post->img = $request->input('img_link');
        $post->story = $request->input('story');
        $post->views = $request->input('views');
        $post->rating = $request->input('rating');
        $post->runtime = $request->input('runtime');
        $post->triller = $request->input('triller');
        $post->year = $request->input('year');
        $post->show = $request->input('show');
        $post->sticker_text = $request->input('sticker_text');
        $post->sticker_color = $request->input('sticker_color');
        if ($request->hasFile('post_img')) {
            $file = $request->file('post_img');
            $filename = time() . '_' . str_replace(['(', ')'], '_', $file->getClientOriginalName());
            $imageUrl = 'photos/uploads/' . $filename;
            $file->move(public_path('photos/uploads'), $filename);
            $post->img = '/' . $imageUrl;
        }
        if ($request->input('pin') == 'on') {
            $maxPinIndex = Post::max('pin_index') ?? 0;
            $post->pin_index = $maxPinIndex + 1;
        }

        $post->opt = $request->input('opt');
        if ($post->opt == 2) {
            $post->num = $request->input('ep_num');
            $post->season_id = $request->input('selected_season');
        }

        $watch_servers = $request->input('watch_servers') ? explode("\r\n", trim($request->input('watch_servers'))) : [];
        $down_servers_1080 = $request->input('post_down_servers_1080') ? explode("\r\n", trim($request->input('post_down_servers_1080'))) : [];
        $down_servers_720 = $request->input('post_down_servers_720') ? explode("\r\n", trim($request->input('post_down_servers_720'))) : [];
        $down_servers_480 = $request->input('post_down_servers_480') ? explode("\r\n", trim($request->input('post_down_servers_480'))) : [];
        $down_servers_360 = $request->input('post_down_servers_360') ? explode("\r\n", trim($request->input('post_down_servers_360'))) : [];
        $post_down_servers_multi = $request->input('post_down_servers_multi') ? explode("\r\n", trim($request->input('post_down_servers_multi'))) : [];

        $new_down_servers = [];
        foreach ($post_down_servers_multi as $server) {
            if (trim($server)) $new_down_servers[] = ['size' => 10000, 'code' => trim($server)];
        }
        foreach ($down_servers_1080 as $server) {
            if (trim($server)) $new_down_servers[] = ['size' => 1080, 'code' => trim($server)];
        }
        foreach ($down_servers_720 as $server) {
            if (trim($server)) $new_down_servers[] = ['size' => 720, 'code' => trim($server)];
        }
        foreach ($down_servers_480 as $server) {
            if (trim($server)) $new_down_servers[] = ['size' => 480, 'code' => trim($server)];
        }
        foreach ($down_servers_360 as $server) {
            if (trim($server)) $new_down_servers[] = ['size' => 360, 'code' => trim($server)];
        }

        $post->watch_servers = $watch_servers;
        $post->down_servers = $new_down_servers;

        $post->save();

        $post->categories()->detach();
        if (!empty($request->input('selected_categs'))) foreach ($request->input('selected_categs') as $category_name) {
            $category = Category::where('name', $category_name)->first();
            if (!$category) {
                $category = new Category();
                $category->name = $category_name;
                $category->slug = strtolower(str_replace(' ', '-', $category_name));
                $category->save();
            }
            try {
                if (!$post->categories->contains($category->id))
                    $post->categories()->attach($category->id);
            } catch (\Exception $e) {
            }
        }
        $post->types()->detach();
        if (!empty($request->input('selected_types'))) foreach ($request->input('selected_types') as $type_name) {
            $type = Type::where('name', $type_name)->first();
            if (!$type) {
                $type = new Type();
                $type->name = $type_name;
                $type->slug = strtolower(str_replace(' ', '-', $type_name));
                $type->save();
            }
            try {
                if (!$post->types->contains($type->id))
                    $post->types()->attach($type->id);
            } catch (\Exception $e) {
            }
        }
        $post->qualities()->detach();
        if (!empty($request->input('selected_qualities'))) foreach ($request->input('selected_qualities') as $quality_name) {
            $quality = Quality::where('name', $quality_name)->first();
            if (!$quality) {
                $quality = new Quality();
                $quality->name = $quality_name;
                $quality->slug = strtolower(str_replace(' ', '-', $quality_name));
                $quality->save();
            }
            try {
                if (!$post->qualities->contains($quality->id))
                    $post->qualities()->attach($quality->id);
            } catch (\Exception $e) {
            }
        }
        $post->actors()->detach();
        if (!empty($request->input('selected_actors'))) foreach ($request->input('selected_actors') as $actor_name) {
            $person = Person::where('name', $actor_name)->first();
            if (!$person) {
                $person = new Person();
                $person->name = $actor_name;
                $person->slug = strtolower(str_replace(' ', '-', $actor_name));
                $person->save();
            }
            try {
                if (!$post->actors->contains($person->id))
                    $post->actors()->attach($person->id);
            } catch (\Exception $e) {
            }
        }
        $post->directors()->detach();
        if (!empty($request->input('selected_directors'))) foreach ($request->input('selected_directors') as $director_name) {
            $person = Person::where('name', $director_name)->first();
            if (!$person) {
                $person = new Person();
                $person->name = $director_name;
                $person->slug = strtolower(str_replace(' ', '-', $director_name));
                $person->save();
            }
            try {
                if (!$post->directors->contains($person->id))
                    $post->directors()->attach($person->id);
            } catch (\Exception $e) {
            }
        }
        $post->writers()->detach();
        if (!empty($request->input('selected_writers'))) foreach ($request->input('selected_writers') as $writer_name) {
            $person = Person::where('name', $writer_name)->first();
            if (!$person) {
                $person = new Person();
                $person->name = $writer_name;
                $person->slug = strtolower(str_replace(' ', '-', $writer_name));
                $person->save();
            }
            try {
                if (!$post->writers->contains($person->id))
                    $post->writers()->attach($person->id);
            } catch (\Exception $e) {
            }
        }

        return redirect(route('admin.articles.all.get'));
    }

    public function articles_copy_page(Request $request): View
    {
        $post = Post::where('id', $request->id)
            ->with('categories')
            ->with('types')
            ->with('qualities')
            ->with('actors')
            ->with('directors')
            ->with('writers')
            ->with('season')
            ->firstorfail();

        $categories = Category::all();
        $types = Type::all();
        $qualities = Quality::all();

        return view('pages/admin/articles/copy', compact('post', 'categories', 'types', 'qualities'));
    }

    public function articles_copy_from_season(Request $request)
    {
        $post = Season::where('id', $request->id)
            ->with('categories')
            ->with('types')
            ->with('qualities')
            ->with('actors')
            ->with('directors')
            ->with('writers')
            ->firstorfail();

        $post->season = Season::where('id', $post->id)->first();
        $post->opt = 2;

        $categories = Category::all();
        $types = Type::all();
        $qualities = Quality::all();

        return view('pages/admin/articles/copy', compact('post', 'categories', 'types', 'qualities'));
    }

    public function articles_delete(Request $request)
    {
        $page = $request->input('page') ? $request->input('page') : 1;
        $pinned = $request->input('pinned');
        $search = $request->input('s');
        $hidden = $request->input('hidden');

        $post = Post::where('id', $request->id)->firstorfail();
        $post->delete();

        $route = route('admin.articles.all.get', ['page' => $page, 'pinned' => $pinned, 's' => $search, 'hidden' => $hidden]);
        return redirect($route);
    }

    public function articles_update_time(Request $request)
    {
        $page = $request->input('page') ? $request->input('page') : 1;
        $pinned = $request->input('pinned');
        $search = $request->input('s');
        $hidden = $request->input('hidden');

        $post = Post::where('id', $request->id)->firstorfail();
        $post->created_at = Carbon::now();

        if ($post->pin_index) {
            $maxPinIndex = Post::max('pin_index') ?? 0;
            $post->pin_index = $maxPinIndex + 1;
        }

        $post->save();

        $route = route('admin.articles.all.get', ['page' => $page, 'pinned' => $pinned, 's' => $search, 'hidden' => $hidden]);
        return redirect($route);
    }

    public function articles_unpin(Request $request)
    {
        $page = $request->input('page') ? $request->input('page') : 1;
        $pinned = $request->input('pinned');
        $search = $request->input('s');
        $hidden = $request->input('hidden');

        $post = Post::where('id', $request->id)->firstorfail();
        $post->pin_index = null;
        $post->save();

        $route = route('admin.articles.all.get', ['page' => $page, 'pinned' => $pinned, 's' => $search, 'hidden' => $hidden]);
        return redirect($route);
    }

    public function articles_pin(Request $request)
    {
        $page = $request->input('page') ? $request->input('page') : 1;
        $pinned = $request->input('pinned');
        $search = $request->input('s');
        $hidden = $request->input('hidden');

        $post = Post::where('id', $request->id)->firstorfail();
        $max_pin_index = Post::max('pin_index') ?? 0;
        $post->pin_index = $max_pin_index + 1;
        $post->save();

        $route = route('admin.articles.all.get', ['page' => $page, 'pinned' => $pinned, 's' => $search, 'hidden' => $hidden]);
        return redirect($route);
    }

    public function articles_hide(Request $request)
    {
        $page = $request->input('page') ? $request->input('page') : 1;
        $pinned = $request->input('pinned');
        $search = $request->input('s');
        $hidden = $request->input('hidden');

        $post = Post::where('id', $request->id)->firstorfail();
        $post->show = 0;
        $post->save();

        $route = route('admin.articles.all.get', ['page' => $page, 'pinned' => $pinned, 's' => $search, 'hidden' => $hidden]);
        return redirect($route);
    }

    public function articles_show(Request $request)
    {
        $page = $request->input('page') ? $request->input('page') : 1;
        $pinned = $request->input('pinned');
        $search = $request->input('s');
        $hidden = $request->input('hidden');

        $post = Post::where('id', $request->id)->firstorfail();
        $post->show = 1;
        $post->save();

        $route = route('admin.articles.all.get', ['page' => $page, 'pinned' => $pinned, 's' => $search, 'hidden' => $hidden]);
        return redirect($route);
    }

    public function seasons_all(Request $request)
    {

        $search = $request->input('s');
        $pinned = (bool)$request->input('pinned');
        $hidden = (bool)$request->input('hidden');

        $settings = Settings::find(1);
        $currentPage = $request->input('page', 1);
        $offset = ($currentPage - 1) * ($settings->per_page ?? 50);

        $seasons = Season::query();
        if ($search) $seasons = $seasons->where('title', 'like', '%' . $search . '%');
        if ($pinned) $seasons = $seasons->whereNotNull('pin_index');
        if ($hidden) $seasons = $seasons->where('show', 0);

        $totalPosts = $seasons->count();
        $lastPage = ceil($totalPosts / $settings->per_page);
        $pagination = ['currentPage' => $currentPage, 'lastPage' => $lastPage];

        $seasons = $seasons->orderBy('created_at', 'desc')
            ->with('categories')
            ->offset($offset)
            ->limit($settings->per_page ?? 50)->get();

        return view('pages/admin/seasons/all', compact('seasons', 'pagination'));
    }

    public function seasons_new_page()
    {
        $categories = Category::all();
        $types = Type::all();
        $qualities = Quality::all();

        return view('pages/admin/seasons/new', compact('categories', 'types', 'qualities'));
    }

    public function seasons_new(Request $request)
    {
        $post = new Season();
        $post->title = $request->input('title');

        $originalSlug = strtolower(str_replace(' ', '-', $request->input('title')));
        $originalSlug = str_replace($this->specialChars, '', $originalSlug);
        $slug = $originalSlug;
        $counter = 1;
        while (Season::where('slug', $slug)->exists()) {
            $slug = $originalSlug . '-' . $counter++;
        }
        $post->slug = $slug;
        $post->img = $request->input('img_link');
        $post->story = $request->input('story');
        $post->rating = $request->input('rating');
        $post->triller = $request->input('triller');
        $post->year = $request->input('year');
        $post->num = $request->input('num');
        $post->serie()->associate($request->input('selected_serie'));
        if ($request->hasFile('post_img')) {
            $file = $request->file('post_img');
            $filename = time() . '_' . str_replace(['(', ')'], '_', $file->getClientOriginalName());
            $imageUrl = 'photos/uploads/' . $filename;
            $file->move(public_path('photos/uploads'), $filename);
            $post->img = '/' . $imageUrl;
        }
        $post->save();


        if (!empty($request->input('selected_categs'))) foreach ($request->input('selected_categs') as $category_name) {
            $category = Category::where('name', $category_name)->first();
            if (!$category) {
                $category = new Category();
                $category->name = $category_name;
                $category->slug = strtolower(str_replace(' ', '-', $category_name));
                $category->save();
//                $this->add_category_to_xml($category);
            }
            try {
                if (!$post->categories()->where('category_id', $category->id)->count())
                    $post->categories()->attach($category->id);
            } catch (\Exception $e) {
            }
        }
        if (!empty($request->input('selected_types'))) foreach ($request->input('selected_types') as $type_name) {
            $type = Type::where('name', $type_name)->first();
            if (!$type) {
                $type = new Type();
                $type->name = $type_name;
                $type->slug = strtolower(str_replace(' ', '-', $type_name));
                $type->save();
            }
            try {
                if (!$post->types()->where('type_id', $type->id)->count())
                    $post->types()->attach($type->id);
            } catch (\Exception $e) {
            }
        }
        if (!empty($request->input('selected_qualities'))) foreach ($request->input('selected_qualities') as $quality_name) {
            $quality = Quality::where('name', $quality_name)->first();
            if (!$quality) {
                $quality = new Quality();
                $quality->name = $quality_name;
                $quality->slug = strtolower(str_replace(' ', '-', $quality_name));
                $quality->save();
            }
            try {
                if (!$post->qualities()->where('quality_id', $quality->id)->count())
                    $post->qualities()->attach($quality->id);
            } catch (\Exception $e) {
            }
        }
        if (!empty($request->input('selected_actors'))) foreach ($request->input('selected_actors') as $actor_name) {
            $person = Person::where('name', $actor_name)->first();
            if (!$person) {
                $person = new Person();
                $person->name = $actor_name;
                $person->slug = strtolower(str_replace(' ', '-', $actor_name));
                $person->save();
            }
            try {
                if (!$post->actors()->where('person_id', $person->id)->count())
                    $post->actors()->attach($person->id);
            } catch (\Exception $e) {
            }
        }
        if (!empty($request->input('selected_directors'))) foreach ($request->input('selected_directors') as $director_name) {
            $person = Person::where('name', $director_name)->first();
            if (!$person) {
                $person = new Person();
                $person->name = $director_name;
                $person->slug = strtolower(str_replace(' ', '-', $director_name));
                $person->save();
            }
            try {
                if (!$post->directors()->where('person_id', $person->id)->count())
                    $post->directors()->attach($person->id);
            } catch (\Exception $e) {
            }
        }
        if (!empty($request->input('selected_writers'))) foreach ($request->input('selected_writers') as $writer_name) {
            $person = Person::where('name', $writer_name)->first();
            if (!$person) {
                $person = new Person();
                $person->name = $writer_name;
                $person->slug = strtolower(str_replace(' ', '-', $writer_name));
                $person->save();
            }
            try {
                if (!$post->writers()->where('person_id', $person->id)->count())
                    $post->writers()->attach($person->id);
            } catch (\Exception $e) {
            }
        }

        return redirect()->route('admin.seasons.all.get');
    }

    public function seasons_update_page(Request $request)
    {
        $post = Season::where('id', $request->id)
            ->with('categories')
            ->with('types')
            ->with('qualities')
            ->with('actors')
            ->with('directors')
            ->with('writers')
            ->with('serie')
            ->firstorfail();

        $categories = Category::all();
        $types = Type::all();
        $qualities = Quality::all();

        return view('pages/admin/seasons/update', compact('post', 'categories', 'types', 'qualities'));
    }

    public function seasons_update(Request $request)
    {
        $post = Season::where('id', $request->id)->firstorfail();
        $post->title = $request->input('title');
        $post->slug = str_replace($this->specialChars, '', $request->input('slug'));
        $post->img = $request->input('img_link');
        $post->story = $request->input('story');
        $post->rating = $request->input('rating');
        $post->triller = $request->input('triller');
        $post->year = $request->input('year');
        $post->num = $request->input('num');
        $post->serie()->associate($request->input('selected_serie'));
        if ($request->hasFile('post_img')) {
            $file = $request->file('post_img');
            $filename = time() . '_' . str_replace(['(', ')'], '_', $file->getClientOriginalName());
            $imageUrl = 'photos/uploads/' . $filename;
            $file->move(public_path('photos/uploads'), $filename);
            $post->img = '/' . $imageUrl;
        }
        $post->save();

        $post->categories()->detach();
        if (!empty($request->input('selected_categs'))) foreach ($request->input('selected_categs') as $category_name) {
            $category = Category::where('name', $category_name)->first();
            if (!$category) {
                $category = new Category();
                $category->name = $category_name;
                $category->slug = strtolower(str_replace(' ', '-', $category_name));
                $category->save();
            }
            try {
                if (!$post->categories()->where('category_id', $category->id)->count())
                    $post->categories()->attach($category->id);
            } catch (\Exception $e) {
            }
        }
        $post->types()->detach();
        if (!empty($request->input('selected_types'))) foreach ($request->input('selected_types') as $type_name) {
            $type = Type::where('name', $type_name)->first();
            if (!$type) {
                $type = new Type();
                $type->name = $type_name;
                $type->slug = strtolower(str_replace(' ', '-', $type_name));
                $type->save();
            }
            try {
                if (!$post->types()->where('type_id', $type->id)->count())
                    $post->types()->attach($type->id);
            } catch (\Exception $e) {
            }
        }
        $post->qualities()->detach();
        if (!empty($request->input('selected_qualities'))) foreach ($request->input('selected_qualities') as $quality_name) {
            $quality = Quality::where('name', $quality_name)->first();
            if (!$quality) {
                $quality = new Quality();
                $quality->name = $quality_name;
                $quality->slug = strtolower(str_replace(' ', '-', $quality_name));
                $quality->save();
            }
            try {
                if (!$post->qualities()->where('quality_id', $quality->id)->count())
                    $post->qualities()->attach($quality->id);
            } catch (\Exception $e) {
            }
        }
        $post->actors()->detach();
        if (!empty($request->input('selected_actors'))) foreach ($request->input('selected_actors') as $actor_name) {
            $person = Person::where('name', $actor_name)->first();
            if (!$person) {
                $person = new Person();
                $person->name = $actor_name;
                $person->slug = strtolower(str_replace(' ', '-', $actor_name));
                $person->save();
            }
            try {
                if (!$post->actors()->where('person_id', $person->id)->count())
                    $post->actors()->attach($person->id);
            } catch (\Exception $e) {
            }
        }
        $post->directors()->detach();
        if (!empty($request->input('selected_directors'))) foreach ($request->input('selected_directors') as $director_name) {
            $person = Person::where('name', $director_name)->first();
            if (!$person) {
                $person = new Person();
                $person->name = $director_name;
                $person->slug = strtolower(str_replace(' ', '-', $director_name));
                $person->save();
            }
            try {
                if (!$post->directors()->where('person_id', $person->id)->count())
                    $post->directors()->attach($person->id);
            } catch (\Exception $e) {
            }
        }
        $post->writers()->detach();
        if (!empty($request->input('selected_writers'))) foreach ($request->input('selected_writers') as $writer_name) {
            $person = Person::where('name', $writer_name)->first();
            if (!$person) {
                $person = new Person();
                $person->name = $writer_name;
                $person->slug = strtolower(str_replace(' ', '-', $writer_name));
                $person->save();
            }
            try {
                if (!$post->writers()->where('person_id', $person->id)->count())
                    $post->writers()->attach($person->id);
            } catch (\Exception $e) {
            }
        }

        return redirect()->route('admin.seasons.all.get');
    }

    public function seasons_copy_page(Request $request)
    {
        $post = Season::where('id', $request->id)
            ->with('categories')
            ->with('types')
            ->with('qualities')
            ->with('actors')
            ->with('directors')
            ->with('writers')
            ->with('serie')
            ->firstorfail();

        $categories = Category::all();
        $types = Type::all();
        $qualities = Quality::all();

        return view('pages/admin/seasons/copy', compact('post', 'categories', 'types', 'qualities'));
    }

    public function seasons_delete(Request $request)
    {
        $page = $request->page ?? 1;
        $search = $request->s ?? null;
        $post = Season::where('id', $request->id)->firstorfail();

        // delete associated episodes
        $episodes = Post::where('season_id', $post->id)->get();
        foreach ($episodes as $episode) {
            $episode->delete();
        }

        $post->delete();

        return redirect()->route('admin.seasons.all.get', ['page' => $page, 's' => $search]);
    }

    public function seasons_copy_from_serie(Request $request)
    {
        $post = Serie::where('id', $request->id)
            ->with('categories')
            ->with('types')
            ->with('qualities')
            ->with('actors')
            ->with('directors')
            ->with('writers')
            ->firstorfail();

        $post->serie = Serie::where('id', $post->id)->first();

        $categories = Category::all();
        $types = Type::all();
        $qualities = Quality::all();

        return view('pages/admin/seasons/copy', compact('post', 'categories', 'types', 'qualities'));
    }

    public function series_all(Request $request)
    {
        $search = $request->input('s');
        $pinned = (bool)$request->input('pinned');
        $hidden = (bool)$request->input('hidden');

        $settings = Settings::find(1);
        $currentPage = $request->input('page', 1);
        $offset = ($currentPage - 1) * ($settings->per_page ?? 50);

        $series = Serie::query();
        if ($search) $series = $series->where('title', 'like', '%' . $search . '%');
        if ($pinned) $series = $series->whereNotNull('pin_index');
        if ($hidden) $series = $series->where('show', 0);

        $totalPosts = $series->count();
        $lastPage = ceil($totalPosts / $settings->per_page);
        $pagination = ['currentPage' => $currentPage, 'lastPage' => $lastPage];

        $series = $series->orderBy('created_at', 'desc')
            ->with('categories')
            ->offset($offset)
            ->limit($settings->per_page ?? 50)->get();

        return view('pages/admin/series/all', compact('series', 'pagination'));
    }

    public function series_new_page()
    {
        $categories = Category::all();
        $types = Type::all();
        $qualities = Quality::all();

        return view('pages/admin/series/new', compact('categories', 'types', 'qualities'));
    }

    public function series_new(Request $request)
    {
        $post = new Serie();
        $post->title = $request->input('title');

        $originalSlug = strtolower(str_replace(' ', '-', $request->input('title')));
        $originalSlug = str_replace($this->specialChars, '', $originalSlug);
        $slug = $originalSlug;
        $counter = 1;
        while (Serie::where('slug', $slug)->exists()) {
            $slug = $originalSlug . '-' . $counter++;
        }
        $post->slug = $slug;
        $post->img = $request->input('img_link');
        $post->story = $request->input('story');
        $post->rating = $request->input('rating');
        $post->triller = $request->input('triller');
        $post->year = $request->input('year');
        if ($request->hasFile('post_img')) {
            $file = $request->file('post_img');
            $filename = time() . '_' . str_replace(['(', ')'], '_', $file->getClientOriginalName());
            $imageUrl = 'photos/uploads/' . $filename;
            $file->move(public_path('photos/uploads'), $filename);
            $post->img = '/' . $imageUrl;
        }
        $post->save();

        if (!empty($request->input('selected_categs'))) foreach ($request->input('selected_categs') as $category_name) {
            $category = Category::where('name', $category_name)->first();
            if (!$category) {
                $category = new Category();
                $category->name = $category_name;
                $category->slug = strtolower(str_replace(' ', '-', $category_name));
                $category->save();
//                $this->add_category_to_xml($category);
            }
            try {
                if (!$post->categories()->where('category_id', $category->id)->count())
                    $post->categories()->attach($category->id);
            } catch (\Exception $e) {
            }
        }
        if (!empty($request->input('selected_types'))) foreach ($request->input('selected_types') as $type_name) {
            $type = Type::where('name', $type_name)->first();
            if (!$type) {
                $type = new Type();
                $type->name = $type_name;
                $type->slug = strtolower(str_replace(' ', '-', $type_name));
                $type->save();
            }
            try {
                if (!$post->types()->where('type_id', $type->id)->count())
                    $post->types()->attach($type->id);
            } catch (\Exception $e) {
            }
        }
        if (!empty($request->input('selected_qualities'))) foreach ($request->input('selected_qualities') as $quality_name) {
            $quality = Quality::where('name', $quality_name)->first();
            if (!$quality) {
                $quality = new Quality();
                $quality->name = $quality_name;
                $quality->slug = strtolower(str_replace(' ', '-', $quality_name));
                $quality->save();
            }
            try {
                if (!$post->qualities()->where('quality_id', $quality->id)->count())
                    $post->qualities()->attach($quality->id);
            } catch (\Exception $e) {
            }
        }
        if (!empty($request->input('selected_actors'))) foreach ($request->input('selected_actors') as $actor_name) {
            $person = Person::where('name', $actor_name)->first();
            if (!$person) {
                $person = new Person();
                $person->name = $actor_name;
                $person->slug = strtolower(str_replace(' ', '-', $actor_name));
                $person->save();
            }
            try {
                if (!$post->actors()->where('person_id', $person->id)->count())
                    $post->actors()->attach($person->id);
            } catch (\Exception $e) {
            }
        }
        if (!empty($request->input('selected_directors'))) foreach ($request->input('selected_directors') as $director_name) {
            $person = Person::where('name', $director_name)->first();
            if (!$person) {
                $person = new Person();
                $person->name = $director_name;
                $person->slug = strtolower(str_replace(' ', '-', $director_name));
                $person->save();
            }
            try {
                if (!$post->directors()->where('person_id', $person->id)->count())
                    $post->directors()->attach($person->id);
            } catch (\Exception $e) {
            }
        }
        if (!empty($request->input('selected_writers'))) foreach ($request->input('selected_writers') as $writer_name) {
            $person = Person::where('name', $writer_name)->first();
            if (!$person) {
                $person = new Person();
                $person->name = $writer_name;
                $person->slug = strtolower(str_replace(' ', '-', $writer_name));
                $person->save();
            }
            try {
                if (!$post->writers()->where('person_id', $person->id)->count())
                    $post->writers()->attach($person->id);
            } catch (\Exception $e) {
            }
        }

        return redirect()->route('admin.series.all.get');
    }

    public function series_copy_page(Request $request)
    {
        $post = Serie::where('id', $request->id)
            ->with('categories')
            ->with('types')
            ->with('qualities')
            ->with('actors')
            ->with('directors')
            ->with('writers')
            ->firstorfail();

        $categories = Category::all();
        $types = Type::all();
        $qualities = Quality::all();

        return view('pages/admin/series/copy', compact('post', 'categories', 'types', 'qualities'));
    }

    public function series_update_page(Request $request)
    {
        $post = Serie::where('id', $request->id)
            ->with('categories')
            ->with('types')
            ->with('qualities')
            ->with('actors')
            ->with('directors')
            ->with('writers')
            ->firstorfail();

        $categories = Category::all();
        $types = Type::all();
        $qualities = Quality::all();

        return view('pages/admin/series/update', compact('post', 'categories', 'types', 'qualities'));
    }

    public function series_update(Request $request)
    {
        $post = Serie::where('id', $request->id)->firstorfail();
        $post->title = $request->input('title');

        $post->slug = str_replace($this->specialChars, '', $request->input('slug'));
        $post->story = $request->input('story');
        $post->rating = $request->input('rating');
        $post->triller = $request->input('triller');
        $post->year = $request->input('year');

        $post->img = $request->input('img_link');
        if ($request->hasFile('post_img')) {
            $file = $request->file('post_img');
            $filename = time() . '_' . str_replace(['(', ')'], '_', $file->getClientOriginalName());
            $imageUrl = 'photos/uploads/' . $filename;
            $file->move(public_path('photos/uploads'), $filename);
            $post->img = '/' . $imageUrl;
        }

        $post->save();

        $post->categories()->detach();
        if (!empty($request->input('selected_categs'))) foreach ($request->input('selected_categs') as $category_name) {
            $category = Category::where('name', $category_name)->first();
            if (!$category) {
                $category = new Category();
                $category->name = $category_name;
                $category->slug = strtolower(str_replace(' ', '-', $category_name));
                $category->save();
            }
            try {
                if (!$post->categories()->where('category_id', $category->id)->count())
                    $post->categories()->attach($category->id);
            } catch (\Exception $e) {
            }
        }
        $post->types()->detach();
        if (!empty($request->input('selected_types'))) foreach ($request->input('selected_types') as $type_name) {
            $type = Type::where('name', $type_name)->first();
            if (!$type) {
                $type = new Type();
                $type->name = $type_name;
                $type->slug = strtolower(str_replace(' ', '-', $type_name));
                $type->save();
            }
            try {
                if (!$post->types()->where('type_id', $type->id)->count())
                    $post->types()->attach($type->id);
            } catch (\Exception $e) {
            }
        }
        $post->qualities()->detach();
        if (!empty($request->input('selected_qualities'))) foreach ($request->input('selected_qualities') as $quality_name) {
            $quality = Quality::where('name', $quality_name)->first();
            if (!$quality) {
                $quality = new Quality();
                $quality->name = $quality_name;
                $quality->slug = strtolower(str_replace(' ', '-', $quality_name));
                $quality->save();
            }
            try {
                if (!$post->qualities()->where('quality_id', $quality->id)->count())
                    $post->qualities()->attach($quality->id);
            } catch (\Exception $e) {
            }
        }
        $post->actors()->detach();
        if (!empty($request->input('selected_actors'))) foreach ($request->input('selected_actors') as $actor_name) {
            $person = Person::where('name', $actor_name)->first();
            if (!$person) {
                $person = new Person();
                $person->name = $actor_name;
                $person->slug = strtolower(str_replace(' ', '-', $actor_name));
                $person->save();
            }
            try {
                if (!$post->actors()->where('person_id', $person->id)->count())
                    $post->actors()->attach($person->id);
            } catch (\Exception $e) {
            }
        }
        $post->directors()->detach();
        if (!empty($request->input('selected_directors'))) foreach ($request->input('selected_directors') as $director_name) {
            $person = Person::where('name', $director_name)->first();
            if (!$person) {
                $person = new Person();
                $person->name = $director_name;
                $person->slug = strtolower(str_replace(' ', '-', $director_name));
                $person->save();
            }
            try {
                if (!$post->directors()->where('person_id', $person->id)->count())
                    $post->directors()->attach($person->id);
            } catch (\Exception $e) {
            }
        }
        $post->writers()->detach();
        if (!empty($request->input('selected_writers'))) foreach ($request->input('selected_writers') as $writer_name) {
            $person = Person::where('name', $writer_name)->first();
            if (!$person) {
                $person = new Person();
                $person->name = $writer_name;
                $person->slug = strtolower(str_replace(' ', '-', $writer_name));
                $person->save();
            }
            try {
                if (!$post->writers()->where('person_id', $person->id)->count())
                    $post->writers()->attach($person->id);
            } catch (\Exception $e) {
            }
        }

        return redirect()->route('admin.series.all.get');
    }

    public function series_delete(Request $request)
    {
        $page = $request->page ?? 1;
        $search = $request->s ?? null;
        $post = Serie::where('id', $request->id)->firstorfail();

        // delete associated seasons and episodes
        $seasons = Season::where('serie_id', $post->id)->get();
        foreach ($seasons as $season) {
            $episodes = Post::where('season_id', $season->id)->get();
            foreach ($episodes as $episode) {
                $episode->delete();
            }
            $season->delete();
        }

        $post->delete();
        return redirect()->route('admin.series.all.get', ['page' => $page, 's' => $search]);
    }

    public function site_settings_page()
    {
        $site_settings = Settings::find(1);
        return view('pages/admin/settings/site_settings', compact('site_settings'));
    }

    public function update_site_settings(Request $request)
    {
        $site_settings = Settings::find(1);
        $site_settings->admin_link = $request->settings_admin_link;
        $site_settings->per_page = $request->per_page;
        $site_settings->site_name = $request->site_name;
        $site_settings->site_desc = $request->site_desc;
        $site_settings->keywords = $request->keywords;
        $site_settings->site_footer = $request->site_footer;

        $site_logo = [];
        $site_logo['style'] = [
            'min' => $request->style_min,
            'max' => $request->style_max,
        ];
        $site_logo['en'] = [
            't1' => $request->en_t1,
            't2' => $request->en_t2,
            't3' => $request->en_t3,
        ];
        $site_logo['ar'] = [
            't1' => $request->ar_t1,
            't2' => $request->ar_t2,
        ];
        $site_settings->site_logo = $site_logo;

        if ($request->hasFile('site_icon')) {
            $file = $request->file('site_icon');
            $filename = 'faveicon.ico';
            $file->move(public_path(''), $filename);
            $site_settings->icon_index = $site_settings->icon_index + 1;
        }

        $site_settings->save();

        return redirect($site_settings->admin_link . '/site_settings');
    }

    public function site_settings_scripts_page()
    {
        $site_settings_scripts = Settings_scripts::find(1);
        return view('pages/admin/settings/site_settings_scripts', compact('site_settings_scripts'));
    }

    public function update_site_settings_scripts(Request $request)
    {
        $site_settings_scripts = Settings_scripts::find(1);
        $site_settings_scripts->head = $request->head_scripts;
        $site_settings_scripts->fotter = $request->fotter_scripts;
        $site_settings_scripts->save();
        return redirect()->back();
    }

    public function bar_page(Request $request)
    {
        $id = $request->id;
        $bar = Bar::find($id)->firstorfail();
        if ($bar) $bar->setHeaderItems();

        $categories = Category::all();

        return view('pages/admin/settings/bar', compact('bar', 'categories'));
    }

    public function bar_add_item(Request $request)
    {
        $id = $request->id;
        $item = $request->item;
        $bar = Bar::find($id)->firstorfail();

        $items = $bar->items;
        $items[] = $item;
        $bar->items = $items;

        $bar->save();

        return response()->json(['items' => $items]);
    }

    public function bar_update(Request $request)
    {
        $id = $request->id;
        $bar = Bar::find($id)->firstorfail();

        $fixed_items = [];
        foreach ($request->items as $item) {
            $bar_item = [];
            if (isset($item['children']) && count($item['children']) > 0) {
                $bar_item['type'] = 'multi';
                $bar_item['name'] = $item['text'];
                $bar_item['items'] = [];
                foreach ($item['children'] as $child) {
                    $child_item = [];
                    if ($child['type'] == 'categ') {
                        $child_item['type'] = 'categ';
                        $child_item['value'] = (int)$child['value'];
                        $bar_item['items'][] = $child_item;
                    } else {
                        $child_item['type'] = 'link';
                        $child_item['name'] = $child['text'] ?? '';
                        $child_item['link'] = $child['link'] ?? '';
                        $bar_item['items'][] = $child_item;
                    }
                }
                $fixed_items[] = $bar_item;
            } else {
                $bar_item['type'] = 'single';
                $bar_item['item'] = [];
                if (isset($item['type']) && $item['type'] == 'categ') {
                    $bar_item['item']['type'] = 'categ';
                    $bar_item['item']['value'] = $item['value'];
                    $fixed_items[] = $bar_item;
                } else {
                    $bar_item['item']['type'] = 'link';
                    $bar_item['item']['name'] = $item['text'] ?? '';
                    $bar_item['item']['link'] = $item['value'] ?? '';
                    $fixed_items[] = $bar_item;
                }
            }
        }

        $bar->items = $fixed_items;
        $bar->save();

        return response()->json(['items' => $bar->items]);
    }

    public function categories_all()
    {
        $categories = Category::all();
        return view('pages/admin/terms/categories/all', compact('categories'));
    }

    public function categories_new_page()
    {
        return view('pages/admin/terms/categories/new');
    }

    public function categories_new(Request $request)
    {
        $category = new Category();
        $category->name = $request->name;
        $category->slug = str_replace(' ', '-', $request->name);
        $category->desc = $request->desc;

        $category->save();
//        $this->add_category_to_xml($category);

        return redirect()->route('admin.categories.all.get');
    }

    public function categories_delete(Request $request)
    {
        $id = $request->id;

        $category = Category::where('id', $id)->firstorfail();
        $category->delete();
        return redirect()->route('admin.categories.all.get');
    }

    public function categories_update_page(Request $request)
    {
        $id = $request->id;
        $category = Category::where('id', $id)->firstorfail();
        return view('pages/admin/terms/categories/update', compact('category'));
    }

    public function categories_update(Request $request)
    {
        $id = $request->id;
        $category = Category::where('id', $id)->firstorfail();
        $category->name = $request->name;
        $category->slug = $request->slug;
        $category->desc = $request->desc;

        $category->save();

        return redirect()->route('admin.categories.all.get');
    }

    public function types_all()
    {
        $types = Type::all();
        return view('pages/admin/terms/types/all', compact('types'));
    }

    public function qualities_all()
    {
        $qualities = Quality::all();
        return view('pages/admin/terms/qualities/all', compact('qualities'));
    }

    public function types_new(Request $request)
    {
        $type = new Type();
        $type->name = $request->name;
        $type->slug = str_replace(' ', '-', $request->name);

        $type->save();

        return redirect()->route('admin.types.all.get');
    }

    public function qualities_new(Request $request)
    {
        $quality = new Quality();
        $quality->name = $request->name;
        $quality->slug = str_replace(' ', '-', $request->name);

        $quality->save();

        return redirect()->route('admin.qualities.all.get');
    }

    public function types_update(Request $request)
    {
        $id = $request->id;
        $type = Type::where('id', $id)->firstorfail();
        $type->name = $request->name;
        $type->slug = str_replace(' ', '-', $request->name);

        $type->save();

        return redirect()->route('admin.types.all.get');
    }

    public function qualities_update(Request $request)
    {
        $id = $request->id;
        $quality = Quality::where('id', $id)->firstorfail();
        $quality->name = $request->name;
        $quality->slug = str_replace(' ', '-', $request->name);

        $quality->save();

        return redirect()->route('admin.qualities.all.get');
    }

    public function types_delete(Request $request)
    {
        $id = $request->id;

        $type = Type::where('id', $id)->firstorfail();
        $type->delete();
        return redirect()->route('admin.types.all.get');
    }

    public function qualities_delete(Request $request)
    {
        $id = $request->id;

        $quality = Quality::where('id', $id)->firstorfail();
        $quality->delete();
        return redirect()->route('admin.qualities.all.get');
    }

    public function watch_servers_all()
    {
        $servers = WatchServer::query()
            ->orderBy('rank', 'asc')
            ->orderBy('id', 'desc')
            ->get();
        return view('pages/admin/servers/watch_servers', compact('servers'));
    }

    public function watch_servers_new(Request $request)
    {
        $server = new WatchServer();
        $server->name = $request->name;
        $server->rank = $request->rank;
        $server->remove = $request->remove;
        $server->add = $request->add;
        $server->type = $request->type;

        if ($request->hasFile('img')) {
            $file = $request->file('img');
            $filename = time() . '.webp';
            $file->move(public_path('photos/servers-images'), $filename);
            $server->img = '/photos/servers-images/' . $filename;
        }

        $server->save();
        return redirect()->route('admin.watch_servers.all.get');
    }

    public function watch_servers_update(Request $request)
    {
        $new_servers = $request->data;
        $servers = WatchServer::query()
            ->orderBy('rank', 'asc')
            ->orderBy('id', 'desc')
            ->get();

        $fixed_servers = [];
        $rank = 0;
        foreach ($new_servers as $new_server) {
            // get server with same id
            $match = $servers->where('id', $new_server['id'])->first();
            // remove the match server from servers
            $servers = $servers->where('id', '!=', $new_server['id']);
            // set the new rank
            $rank++;
            $match->rank = $rank;
            // push the match server to fixed servers
            $fixed_servers[] = $match;
        }

        // remove the rest of servers
        foreach ($servers as $server) {
            $server->delete();
        }

        // save the fixed servers
        foreach ($fixed_servers as $server) {
            $server->save();
        }

        return response()->json($request->all());
    }

    public function down_servers_all()
    {
        $servers = DownServer::query()
            ->orderBy('rank', 'asc')
            ->orderBy('id', 'desc')
            ->get();
        return view('pages/admin/servers/down_servers', compact('servers'));
    }

    public function down_servers_new(Request $request)
    {
        $server = new DownServer();
        $server->name = $request->name;
        $server->rank = $request->rank;
        $server->remove = $request->remove;
        $server->add = $request->add;

        $server->save();
        return redirect()->route('admin.down_servers.all.get');
    }

    public function down_servers_update(Request $request)
    {
        $new_servers = $request->data;
        $servers = DownServer::query()
            ->orderBy('rank', 'asc')
            ->orderBy('id', 'desc')
            ->get();

        $fixed_servers = [];
        $rank = 0;
        foreach ($new_servers as $new_server) {
            // get server with same id
            $match = $servers->where('id', $new_server['id'])->first();
            // remove the match server from servers
            $servers = $servers->where('id', '!=', $new_server['id']);
            // set the new rank
            $rank++;
            $match->rank = $rank;
            // push the match server to fixed servers
            $fixed_servers[] = $match;
        }

        // remove the rest of servers
        foreach ($servers as $server) {
            $server->delete();
        }

        // save the fixed servers
        foreach ($fixed_servers as $server) {
            $server->save();
        }

        return response()->json($request->all());
    }

    public function glide_page()
    {
        $glide_posts = GlidePost::with('post')->orderBy('id', 'desc')->get();
        return view('pages/admin/settings/glide', compact('glide_posts'));
    }

    public function glide_new_post(Request $request)
    {
        $id_post = $request->new_post;
        $new_glide_post = new GlidePost();
        $new_glide_post->id_post = $id_post;
        $new_glide_post->save();
        return redirect()->route('admin.glide.get');
    }

    public function glide_remove_post(Request $request)
    {
        $id = $request->id;
        $glide_post = GlidePost::where('id', $id)->firstorfail();
        $glide_post->delete();
        return redirect()->route('admin.glide.get');
    }

    public function social_media_page()
    {
        $social_media = SocialMedia::where('id', '!=', 1)->get();
        return view('pages/admin/settings/social_media', compact('social_media'));
    }

    public function social_media_new(Request $request)
    {
        $social_media = new SocialMedia();
        $social_media->name = $request->name;
        $social_media->icon = $request->icon;
        $social_media->link = $request->link;
        $social_media->save();
        return redirect()->route('admin.social_media.get');
    }

    public function social_media_update(Request $request)
    {
        $filteredItems = [];

        foreach ($request->all() as $key => $value) {
            if (strpos($key, "sm_") === 0) {
                $id = substr($key, 3); // Remove "sm_" from the key to get the ID
                $sm = SocialMedia::where('id', $id)->first();
                $sm->link = $value;
                $sm->save();
            }
        }

        return redirect()->route('admin.social_media.get');
    }

    public function social_media_delete(Request $request)
    {
        $id = $request->id;
        $social_media = SocialMedia::where('id', $id)->firstorfail();
        $social_media->delete();
        return redirect()->route('admin.social_media.get');
    }

    public function accounts_all()
    {
        $users = User::where('id', '!=', 1)->get();
        return view('pages/admin/settings/accounts', compact('users'));
    }

    public function accounts_update_info(Request $request)
    {
        $id = $request->id;
        $user = User::where('id', $id)->firstOrFail();

        if ($user->email !== $request->email && User::where('email', $request->email)->exists()) {
            return response()->json(['error' => 'email_taken'], 422);
        }

        $user->name = $request->name;
        $user->email = $request->email;
        $user->save();

        return response()->json(['success' => true]);
    }

    public function accounts_update_password(Request $request)
    {
        $id = $request->id;
        $user = User::where('id', $id)->firstOrFail();

        $user->password = Hash::make($request->password);
        $user->save();

        return response()->json(['success' => true]);
    }

    public function accounts_new(Request $request)
    {
        $user = new User();
        $user->name = $request->new_name;
        $user->email = $request->new_email;

        if (User::where('email', $request->new_email)->exists()) {
            return response()->json(['error' => 'email_taken'], 422);
        }

        $user->password = Hash::make($request->new_password);
        $user->save();

        return response()->json(['success' => true]);
    }

    public function accounts_delete(Request $request)
    {
        $id = $request->id;
        $user = User::where('id', $id)->firstOrFail();
        $user->delete();
        return redirect()->route('admin.accounts.all.get');
    }

    public function sitemap_page()
    {
        return view('pages/admin/settings/sitemap');
    }

    public function update_sitemap(Request $request)
    {
        $old_url = $request->old_url;
        $new_url = $request->new_url;

        $robotsPath = public_path('robots.txt');
        if (File::exists($robotsPath)) {
            $robotsContent = File::get($robotsPath);
            $updatedRobotsContent = str_replace($old_url, $new_url, $robotsContent);
            File::put($robotsPath, $updatedRobotsContent);
        }

        $publicPath = public_path();

        $files = File::files($publicPath);

        foreach ($files as $file) {
            $filename = $file->getFilename();
            $extension = pathinfo($filename, PATHINFO_EXTENSION);
            if (!($extension === 'xml')) continue;

            $xmlPath = $file->getPathname();
            $xmlContent = File::get($xmlPath);
            $updatedXmlContent = str_replace($old_url, $new_url, $xmlContent);

            File::put($xmlPath, $updatedXmlContent);
        }

        return redirect()->route('admin.sitemap.get');
    }
}
