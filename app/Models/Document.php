<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

class Document extends Model
{
    use HasFactory, LogsActivity;

    protected static $logName = 'document_activity';

    protected static $logAttributes = ['name', 'file_path', 'extension', 'size', 'mime_type'];

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logOnly(static::$logAttributes)
            ->useLogName(static::$logName);
    }

    protected $fillable = ['name', 'file_path', 'extension', 'size', 'mime_type'];

    public function documentable(): MorphTo
    {
        return $this->morphTo();
    }
}
