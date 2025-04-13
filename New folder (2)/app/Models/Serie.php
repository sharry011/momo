<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Artisan;

class Serie extends Model
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
    public function seasons()
    {
        return $this->hasMany(Season::class, 'serie_id', 'id');
    }

    public function categories()
    {
        return $this->belongsToMany(Category::class, 'series_categories', 'serie_id', 'category_id');
    }

    public function types()
    {
        return $this->belongsToMany(Type::class, 'series_types', 'serie_id', 'type_id');
    }

    public function qualities()
    {
        return $this->belongsToMany(Quality::class, 'series_qualities', 'serie_id', 'quality_id');
    }

    public function actors()
    {
        return $this->belongsToMany(Person::class, 'series_actors', 'serie_id', 'person_id');
    }

    public function directors()
    {
        return $this->belongsToMany(Person::class, 'series_directors', 'serie_id', 'person_id');
    }

    public function writers()
    {
        return $this->belongsToMany(Person::class, 'series_writers', 'serie_id', 'person_id');
    }
}
