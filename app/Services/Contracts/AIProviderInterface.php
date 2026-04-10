<?php

namespace App\Services\Contracts;

interface AIProviderInterface
{
    public function generateAnswer(string $prompt, array $options = []): array;
}
