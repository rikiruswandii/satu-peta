<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Tags\HasTags;

class Map extends Model
{
    use HasFactory, HasTags, LogsActivity, SoftDeletes;

    public function setTagsAttribute($tags)
    {
        $this->syncTagsWithType($tags, 'article');
    }

    public function getTagsAttribute()
    {
        return $this->tagsWithType('article');
    }

    protected static $logName = 'maps_activity';

    protected static $logAttributes = ['user_id', 'name'];

    protected $with = ['sector'];

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logOnly(static::$logAttributes)
            ->useLogName(static::$logName);
    }

    public function documents(): MorphMany
    {
        return $this->morphMany(Document::class, 'documentable');
    }

    public function regional_agency(): BelongsTo
    {
        return $this->belongsTo(RegionalAgency::class);
    }

    public function sector(): BelongsTo
    {
        return $this->belongsTo(Sector::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    protected $fillable = [
        'user_id',
        'regional_agency_id',
        'sector_id',
        'name',
        'slug',
        'can_download',
        'latitude',
        'longitude',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'can_download' => 'boolean',
    ];
}
