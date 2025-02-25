<?php

namespace App\Models;

use Spatie\Tags\Tag as SpatieTag;

class Tag extends SpatieTag
{
    public function articles()
    {
        return $this->morphedByMany(Article::class, 'taggable', 'taggables');
    }
}
