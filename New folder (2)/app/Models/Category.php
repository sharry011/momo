<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Artisan;

class Category extends Model
{
    use HasFactory;
    public $timestamps = false;


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
}
