<?php

namespace App\Http\Controllers;

use App\Models\Person;
use App\Models\Post;
use App\Models\Season;
use App\Models\Serie;
use PHPHtmlParser\Dom;
use App\Models\Type;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;
use Throwable;
use Illuminate\Http\Request;
use hmerritt\Imdb;
use Stichoza\GoogleTranslate\GoogleTranslate;

class dataProviderController extends Controller
{
//    public function imdb(Request $request)
//    {
//        $imdb_id = $request->id;
//        if (!$imdb_id) return response()->json(['error' => 'id is required'], 400);
//
//        $imdb_provider = new Imdb;
//        $tr_provider = new GoogleTranslate('ar');
//
//        $data = $imdb_provider->film($imdb_id, [
//            'cache'        => true,
//            'curlHeaders'  => ['Accept-Language: en,en-US;q=0.8'],
//        ]);
//
//        $data['plot'] = $tr_provider->translate($data['plot']);
//
//        foreach ($data['genres'] as $key => $value) {
//            $type = Type::where('en_name', $value)->first();
//            if ($type) {
//                $data['genres'][$key] = $type->name;
//                continue;
//            }
//            $data['genres'][$key] = $tr_provider->translate($value);
//        }
//
//        dd($data);
////        return response()->json($data);
//    }

    public function imdb()
    {
        $id = request()->id;
        $tr_provider = new GoogleTranslate('ar');
        $imdb_provider = new Imdb;
        $OMDB_API_KEY = env('OMDB_API_KEY');

        $apiUrl = "http://www.omdbapi.com/?i=${id}&apikey=${OMDB_API_KEY}";
        $response = Http::get($apiUrl);
        $data = $response->json();

        if ($data['Response'] == 'False') {
            $sec_data = $imdb_provider->film($id, [
                'cache'        => true,
                'curlHeaders'  => ['Accept-Language: en,en-US;q=0.8'],
            ]);
            $data['Genre'] = $sec_data['genres'] ?? [];
            $data['Director'] = $sec_data['directors'] ?? [];
            $data['Writer'] = $sec_data['writers'] ?? [];
            // take just the name and make it array
            $data['Actors'] = collect($sec_data['cast'])->filter(function ($value, $key) {
                return isset($value['actor']);
            })->map(function ($value, $key) {
                return $value['actor'];
            })->toArray();

            if (!empty($sec_data['year'])) {
                $data['Year'] = (int) preg_replace('/\D/', '', $sec_data['year']);
                $data['Year'] = Str::limit($data['Year'], 4, '') == 0 ? null : Str::limit($data['Year'], 4, '');
            }
            if (!empty($sec_data['length'])) {
                $data['Runtime'] = $sec_data['length'];
            } else $data['Runtime'] = null;

            if (!empty($sec_data['rating'])) {
                $data['imdbRating'] = $sec_data['rating'];
            }

            if (!empty($sec_data['plot'])) {
                try {
                    $data['Plot'] = $tr_provider->translate($sec_data['plot']);
                } catch (Throwable $e) {
                    $data['Plot'] = $sec_data['plot'];
                }

            }

            foreach ($data['Genre'] as $key => $value) {
                $type = Type::where('en_name', $value)->first();
                if ($type) {
                    $data['Genre'][$key] = $type->name;
                    continue;
                }
                try {
                    $data['Genre'][$key] = $tr_provider->translate($value);
                } catch (Throwable $e) {
                    $data['Genre'][$key] = $value;
                }
            }

            $data['Title'] = $sec_data['title'];

            $data['original_poster'] = $sec_data['poster'];
            $data['Poster'] = '/' . $this->DownloadPoster($data['original_poster'], $id, 'imdb');

            return response()->json($data);
        }
        // Parse to json
        $data['Genre'] = (!empty($data['Genre']) && $data['Genre'] != 'N/A') ? explode(', ', $data['Genre']) : [];
        $data['Director'] = (!empty($data['Director']) && $data['Director'] != 'N/A') ? explode(', ', $data['Director']) : [];
        $data['Writer'] = (!empty($data['Writer']) && $data['Writer'] != 'N/A') ? explode(', ', $data['Writer']) : [];
        $data['Actors'] = (!empty($data['Actors']) && $data['Actors'] != 'N/A') ? explode(', ', $data['Actors']) : [];

        $data = collect($data)->map(function ($value, $key) {
            return $value === "N/A" ? null : $value;
        })->toArray();

        $data = collect($data)->map(function ($value, $key) {
            return $value && $value[0] === null ? [] : $value;
        })->toArray();

        // Parse the year
        if (!empty($data['Year'])) {
            $data['Year'] = (int) preg_replace('/\D/', '', $data['Year']);
            $data['Year'] = Str::limit($data['Year'], 4, '');
        }

        // Parse the runtime
        if (!empty($data['Runtime'])) {
            $data['Runtime'] = (int) preg_replace('/\D/', '', $data['Runtime']);
        }

        foreach ($data['Genre'] as $key => $value) {
            $type = Type::where('en_name', $value)->first();
            if ($type) {
                $data['Genre'][$key] = $type->name;
                continue;
            }
            try {
                $data['Genre'][$key] = $tr_provider->translate($value);
            } catch (Throwable $e) {
                $data['Genre'][$key] = $value;
            }
        }

        // translate the plot
        try {
            $data['Plot'] = $tr_provider->translate($data['Plot']);
        } catch (Throwable $e) {
            $data['Plot'] = $data['Plot'];
        }

        $data['original_poster'] = $data['Poster'];

        $data['Poster'] = '/' . $this->DownloadPoster($data['Poster'], $id, 'imdb');

        if (empty($data['imdbRating'])) {
            try{
                $sec_data = $imdb_provider->film($id, [
                    'cache'        => true,
                    'curlHeaders'  => ['Accept-Language: en,en-US;q=0.8'],
                ]);
                $data['imdbRating'] = $sec_data['rating'];
            } catch (Throwable $e) {
                $data['imdbRating'] = null;
            }
        }

        return response()->json($data);
    }

    public function elcinema(Request $request)
    {
        $code = $request->id;
        $lang = 'ar';
        $dom = new Dom;
        $dom->loadFromUrl('http://www.elcinema.com/'.$lang.'/work/'.$code.'/enrich');
        if ($lang == 'ar') {
            $title = $dom->find('table.large-12',0)->find('tbody')->find('tr',0)->find('td',1)->find('span')->text;
        }else{
            $title = $dom->find('table.large-12',0)->find('tbody')->find('tr',1)->find('td',1)->find('span')->text;
        }
        $Rated = $dom->find('table.large-12',0)->find('tbody')->find('tr',11)->find('td',1)->text;
        $rate = $dom->find('div [class=stars-rating-lg]')[0]->find('span.legend')[0]->text;
        $year = explode(',', $dom->find('table.large-12')[0]->find('tbody')->find('tr')[4]->find('td')[1]->find('span')->text);
        $runtime = $dom->find('table.large-12')[0]->find('tbody')->find('tr')[5]->find('td')[1]->find('span')->text;
        $type = $dom->find('table.large-12')[0]->find('tbody')->find('tr')[6]->find('td')[1]->find('span')->text;
        $Classification = $dom->find('table.large-12')[0]->find('tbody')->find('tr')[10]->find('td')[1]->text;

        $poster = str_replace("_320x_", "", $dom->find("meta[property='og:image']")->getAttribute('content'));

        //Left table
        $releaseDate = 	$dom->find('table.large-12')[1]->find('tbody')->find('tr');
        if(!is_null($releaseDate[0])){
            $releaseDate = $releaseDate[0]->find('td')[0]->find('a')[0]->text;
        }

        $genres = $dom->find('table.large-12')[2]->find('tbody')->find('tr');
        $genre = [];
        foreach ($genres as $key => $value) {
            $genre [] = $value->find('td')[0]->text;
        }
        $countries = $dom->find('table.large-12')[3]->find('tbody')->find('tr')[0]->find('td')[0]->text;
        $language = @$dom->find('table.large-12')[4]->find('tbody')->find('tr')[0]->find('td')[0]->text;
        //Start Get Cast Data
        $cast = $dom->find('#cast-actor')[0]->find('table.large-12')[0]->find('tbody')->find('tr');
        $castAr = [];
        foreach ($cast as $key => $value) {
            $castAr [] = $value->find('td')[1]->find('a')[1]->text;
        }
        $writer = $dom->find('#cast-writer')[0]->find('table.large-12')[0]->find('tbody')->find('tr');
        $writerA = [];
        foreach ($writer as $key => $value) {
            $writerA [] = $value->find('td')[1]->find('a')[1]->text;
        }
        $director = $dom->find('#cast-director')[0]->find('table.large-12')[0]->find('tbody')->find('tr');
        $directorA = [];
        foreach ($director as $key => $value) {
            $directorA [] = $value->find('td')[1]->find('a')[1]->text;
        }
        $producer = $dom->find('#cast-producer')[0]->find('table.large-12')[0]->find('tbody')->find('tr');
        $producerA = [];
        foreach ($producer as $key => $value) {
            $producerA [] = $value->find('td')[1]->find('a')[1]->text;
        }
        if(!is_null($dom->find('div [class=boxed-3]')[2]->find('.large-12')[0]->find('tbody')[0]->find('tr')[0])){
            $story = $dom->find('div [class=boxed-3]')[2]->find('.large-12')[0]->find('tbody')[0]->find('tr')[0]->find('td')[1]->text;
        }else{
            $story = $title;
        }


        $data = [];

        $data['original_poster'] = $poster;

        $data['Poster'] = '/' . $this->DownloadPoster($poster, $code, 'elcinema');

        //Start Add To Array
        $data ['Title'] = $title;
        $data ['Year'] = $year;
        $data ['Rated'] = $Rated;
        $data ['Released'] = $releaseDate;

        $data ['Runtime'] = $runtime == 0 ? null : $runtime;

        $data ['Genre'] = $genre;
        $data ['Director'] = $directorA;
        $data ['Writer'] = $writerA;
        $data ['Actors'] = $castAr;
        $data ['Plot'] = $story;
        $data ['Language'] = $language;
        $data ['Country'] = $countries;
        $data ['Ratings'] = $rate;
        $data ['Type'] = $type;
        $data ['Production'] = implode(",", $producerA);


        return response()->json($data);
    }

    public function search_persons(Request $request)
    {
        $search = $request->search;
        $persons = Person::where('name', 'like', '%' . $search . '%')->take(30)->get();

        return response()->json(['persons' => $persons]);
    }

    public function search_posts(Request $request)
    {
        $search = $request->search;
        $posts = Post::where('title', 'like', '%' . $search . '%')->take(30)->get();

        return response()->json(['posts' => $posts]);
    }

    public function search_seasons(Request $request)
    {
        $search = $request->search;
        $seasons = Season::where('title', 'like', '%' . $search . '%')->take(30)->get();

        return response()->json(['seasons' => $seasons]);
    }

    public function search_series(Request $request)
    {
        $search = $request->search;
        $series = Serie::where('title', 'like', '%' . $search . '%')->take(30)->get();

        return response()->json(['series' => $series]);
    }



    private function DownloadPoster($url, $id, $type)
    {
        $fileExtension = pathinfo($url, PATHINFO_EXTENSION);
        $uniqueFileName = $id . '.' . $fileExtension;
        $imageUrl = 'photos/shares/' . $type . '/' . $uniqueFileName;
        $imagePath = public_path($imageUrl);
        $img_response = Http::get($url);
        file_put_contents($imagePath, $img_response->getBody());

        return $imageUrl;
    }
}
