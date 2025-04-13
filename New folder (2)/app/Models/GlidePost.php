<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GlidePost extends Model
{
    use HasFactory;
    public $timestamps = false;
    public function post() {
        return $this->belongsTo(Post::class, 'id_post');
    }
}
