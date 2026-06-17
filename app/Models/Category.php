<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    protected $guarded = [];

    protected static function booted()
    {
        static::saved(fn () => \Illuminate\Support\Facades\Cache::forget('global_categories'));
        static::deleted(fn () => \Illuminate\Support\Facades\Cache::forget('global_categories'));
    }

    public function subcategory()
    {
        return $this->hasMany(SubCategory::class, 'category_id', 'id');
    }

    public function course()
    {
        return $this->hasMany(Course::class, 'category_id', 'id')
            ->where('approval_status', 'published')
            ->where('status', 1);
    }
}
