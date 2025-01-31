<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

class RegionalAgency extends Model
{
    use HasFactory, LogsActivity, SoftDeletes;

    protected static $logName = 'region_agencies_activity';

    protected static $logAttributes = [ 'user_id','name'];

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logOnly(static::$logAttributes)
            ->useLogName(static::$logName);
    }

    public function map(): HasMany
    {
        return $this->hasMany(Map::class);
    }

    protected $fillable = [
        'id',
        'user_id',
        'name',
        'slug',
    ];
}