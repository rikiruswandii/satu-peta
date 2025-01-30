<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

class Category extends Model
{
    use HasFactory, LogsActivity, SoftDeletes;

    protected static $logName = 'categories_activity';

    protected static $logAttributes = ['title'];

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logOnly(static::$logAttributes)
            ->useLogName(static::$logName);
    }

    public function artikel(): HasMany
    {
        return $this->hasMany(Articles::class);
    }

    protected $table = 'categories';

    protected $fillable = [
        'id',
        'enhancer',
        'name',
        'slug',
    ];
}