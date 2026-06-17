<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SiteInfo extends Model
{
    protected $guarded = [];

    protected static function booted()
    {
        static::saved(fn () => \Illuminate\Support\Facades\Cache::forget('site_info'));
        static::deleted(fn () => \Illuminate\Support\Facades\Cache::forget('site_info'));
    }
}
