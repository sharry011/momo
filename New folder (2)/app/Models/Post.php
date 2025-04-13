<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Artisan;
use Spatie\Feed\Feedable;
use Spatie\Feed\FeedItem;



class Post extends Model implements Feedable

{
    use HasFactory;


    public function toFeedItem(): FeedItem
{
    return FeedItem::create([
        'id' => $this->id,
        'title' => $this->title,
        'summary' => $this->story ?? '',
        'updated' => $this->updated_at,
        'link' => route('episode', ['slug' => $this->slug]),
        'authorName' => $this->author ?? 'Admin',
    ]);
}



    /**
     * Fetch latest posts for RSS feed.
     */
    public static function getFeedItems()
    {
        return static::latest()->take(1000)->get();
    }



    protected static function boot()
    {
        parent::boot();
    
        static::created(function ($model) {
            self::updateSitemap($model, null); 
        });
    
        static::updated(function ($model) {
            $oldSlug = $model->getOriginal('slug');
           if ($oldSlug !== $model->slug) {
        self::updateSitemap($model, $oldSlug);
    }
         
        });
    
        static::deleting(function ($model) {
            $oldSlug = $model->slug; 
            self::updateSitemap($model, $oldSlug, true);
        });
    }
    
    private static function updateSitemap($model, $oldSlug = null, $isDelete = false)
    {
        $params = [
            'model' => get_class($model),
            'oldSlug' => $oldSlug,  
        ];
    
        if ($isDelete) {
            $params['action'] = 'remove'; 
            $params['slug'] = $model->slug; 
        } else {
            $params['slug'] = $model->slug;
        }
    
        \Artisan::call('sitemap:Update', $params);
    }
    
    

    protected $casts = [
        'watch_servers' => 'array',
        'down_servers' => 'array',
    ];

    public function season()
    {
        return $this->belongsTo(Season::class);
    }

    public function categories()
    {
        return $this->belongsToMany(Category::class, 'posts_categories', 'post_id', 'category_id');
    }

    public function types()
    {
        return $this->belongsToMany(Type::class, 'posts_types', 'post_id', 'type_id');
    }

    public function qualities()
    {
        return $this->belongsToMany(Quality::class, 'posts_qualities', 'post_id', 'quality_id');
    }

    public function actors()
    {
        return $this->belongsToMany(Person::class, 'posts_actors', 'post_id', 'person_id');
    }

    public function directors()
    {
        return $this->belongsToMany(Person::class, 'posts_directors', 'post_id', 'person_id');
    }

    public function writers()
    {
        return $this->belongsToMany(Person::class, 'posts_writers', 'post_id', 'person_id');
    }

}
