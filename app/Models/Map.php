<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
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

    // Scope untuk filter berdasarkan regional_agencies
    public function scopeFilterByRegionalAgencies(Builder $query, $regionalAgencies)
    {
        if (! empty($regionalAgencies)) {
            if (is_string($regionalAgencies)) {
                $regionalAgencies = explode(',', $regionalAgencies);
            }
            $query->whereHas('regional_agency', function ($q) use ($regionalAgencies) {
                $q->whereIn('slug', $regionalAgencies);
            });
        }
    }

    // Scope untuk filter berdasarkan sektor
    public function scopeFilterBySector(Builder $query, $sector)
    {
        if (! empty($sector)) {
            $sectors = is_array($sector) ? $sector : [$sector];
            $query->withAnyTags($sectors, 'map');
        }
    }

    // Scope untuk filter berdasarkan kata kunci pencarian
    public function scopeFilterBySearch(Builder $query, $search)
    {
        if (! empty($search)) {
            $query->where('name', 'like', '%'.$search.'%');
        }
    }

    // Scope untuk filter berdasarkan nama regional agency
    public function scopeFilterByRegionalAgenciesCheckbox(Builder $query, $regionalAgenciesCheckbox)
    {
        if (! empty($regionalAgenciesCheckbox)) {
            $regionalAgencies = array_map('trim', (array) $regionalAgenciesCheckbox);
            $query->whereHas('regional_agency', function ($query) use ($regionalAgencies) {
                $query->whereIn('name', $regionalAgencies);
            });
        }
    }
}
