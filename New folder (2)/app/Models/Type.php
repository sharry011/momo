<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Type extends Model
{
    use HasFactory;
    public $timestamps = false;

    public function posts()
    {
        return $this->belongsToMany(Post::class, 'posts_types', 'type_id', 'post_id');
    }

    public function seasons()
    {
        return $this->belongsToMany(Season::class, 'seasons_types', 'type_id', 'season_id');
    }

    public function series()
    {
        return $this->belongsToMany(Serie::class, 'series_types', 'type_id', 'serie_id');
    }
}
