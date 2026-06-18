<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class WebsiteKnowledgeDocument extends Model
{
    protected $guarded = [];

    protected $casts = [
        'published_at' => 'datetime',
    ];

    public const DOC_TYPES = [
        'feature_how_to',
        'faq',
        'policy',
    ];

    public const STATUSES = [
        'draft',
        'published',
    ];

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function updater()
    {
        return $this->belongsTo(User::class, 'updated_by');
    }
}
