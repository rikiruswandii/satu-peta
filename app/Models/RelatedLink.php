<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

class RelatedLink extends Model
{
    use HasFactory, LogsActivity, SoftDeletes;

    protected static $logName = 'related_links_activity';

    protected static $logAttributes = ['user_id'];

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

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    protected $table = 'related_links';

    protected $fillable = [
        'id',
        'user_id',
        'title',
        'url',
        'logo',
        'order',
        'is_active',
    ];
}
