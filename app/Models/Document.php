<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class Document extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'file_path', 'extension', 'size', 'mime_type'];

    public function documentable(): MorphTo
    {
        return $this->morphTo();
    }
}
