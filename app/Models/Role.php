<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

class Role extends Model
{
    use HasFactory, LogsActivity;

    protected static $logName = 'role_activity';

    protected static $logAttributes = ['name'];

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logOnly(static::$logAttributes)
            ->useLogName(static::$logName);
    }

    public function users(): HasMany
    {
        return $this->hasMany(User::class);
    }

    protected $fillable = [
        'name',
    ];
}
