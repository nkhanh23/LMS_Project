<?php

namespace App\Services;

class AiChunkingService
{
    public function splitText(string $text, int $chunkSize = 800, int $overlap = 120): array
    {
        $text = trim(preg_replace('/\s+/u', ' ', $text));

        if ($text === '') {
            return [];
        }

        $chunks = [];
        $length = mb_strlen($text);
        $start = 0;
        $index = 0;

        while ($start < $length) {
            $slice = mb_substr($text, $start, $chunkSize);
            $slice = trim($slice);

            if ($slice !== '') {
                $chunks[] = [
                    'chunk_index' => $index,
                    'content' => $slice,
                    'content_length' => mb_strlen($slice),
                    'meta_json' => null,
                ];
                $index++;
            }

            if (($start + $chunkSize) >= $length) {
                break;
            }

            $start += ($chunkSize - $overlap);
        }

        return $chunks;
    }
}
