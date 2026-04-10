<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class GeminiSetting extends Model
{
    protected $guarded = [];
    protected $casts = [
        'is_enabled' => 'boolean',
        'timeout_seconds' => 'integer',
        'temperature' => 'float',
        'max_output_tokens' => 'integer',
    ];

    public function updater()
    {
        return $this->belongsTo(User::class, 'updated_by');
    }

    public function maskedApiKey(): ?string
    {
        if (!$this->api_key) {
            return null;
        }

        $length = strlen($this->api_key);

        if ($length <= 8) {
            return str_repeat('*', $length);
        }

        return substr($this->api_key, 0, 6)
            . str_repeat('*', max(0, $length - 10))
            . substr($this->api_key, -4);
    }
}
