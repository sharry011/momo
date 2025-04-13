<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Artisan;

class Season extends Model
{
    use HasFactory;

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

    public function episodes()
    {
        return $this->hasMany(Post::class, 'season_id', 'id');
    }

    public function serie()
    {
        return $this->belongsTo(Serie::class);
    }

    public function categories()
    {
        return $this->belongsToMany(Category::class, 'seasons_categories', 'season_id', 'category_id');
    }

    public function types()
    {
        return $this->belongsToMany(Type::class, 'seasons_types', 'season_id', 'type_id');
    }

    public function qualities()
    {
        return $this->belongsToMany(Quality::class, 'seasons_qualities', 'season_id', 'quality_id');
    }

    public function actors()
    {
        return $this->belongsToMany(Person::class, 'seasons_actors', 'season_id', 'person_id');
    }

    public function directors()
    {
        return $this->belongsToMany(Person::class, 'seasons_directors', 'season_id', 'person_id');
    }

    public function writers()
    {
        return $this->belongsToMany(Person::class, 'seasons_writers', 'season_id', 'person_id');
    }
}
