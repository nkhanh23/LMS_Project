<?php

namespace App\Services;

class AiChunkingService
{
    public function splitText(string $text, int $chunkSize = 1000, int $overlap = 150): array
    {
        $normalized = $this->normalizeText($text);

        if ($normalized === '') {
            return [];
        }

        $paragraphs = preg_split('/\n{2,}/u', $normalized) ?: [];
        $chunks = [];
        $buffer = '';
        $index = 0;

        foreach ($paragraphs as $paragraph) {
            $paragraph = trim($paragraph);

            if ($paragraph === '') {
                continue;
            }

            $candidate = trim($buffer . "\n\n" . $paragraph);

            if ($buffer !== '' && mb_strlen($candidate) > $chunkSize) {
                $content = trim($buffer);

                if ($content !== '') {
                    $chunks[] = $this->makeChunkPayload($content, $index);
                    $index++;
                }

                $buffer = $this->tailWithOverlap($content, $overlap);
                $candidate = trim($buffer . "\n\n" . $paragraph);
            }

            if (mb_strlen($candidate) <= $chunkSize) {
                $buffer = $candidate;
                continue;
            }

            $slices = $this->splitLongParagraph($paragraph, $chunkSize, $overlap);

            foreach ($slices as $slice) {
                $chunks[] = $this->makeChunkPayload($slice, $index);
                $index++;
            }

            $buffer = '';
        }

        if (trim($buffer) !== '') {
            $chunks[] = $this->makeChunkPayload(trim($buffer), $index);
        }

        return $chunks;
    }

    private function normalizeText(string $text): string
    {
        $text = str_replace(["\r\n", "\r"], "\n", $text);
        $text = preg_replace("/[ \t]+/u", ' ', $text);
        $text = preg_replace("/\n{3,}/u", "\n\n", $text);

        return trim((string) $text);
    }

    private function splitLongParagraph(string $paragraph, int $chunkSize, int $overlap): array
    {
        $paragraph = trim($paragraph);

        if ($paragraph === '') {
            return [];
        }

        $length = mb_strlen($paragraph);
        $start = 0;
        $parts = [];

        while ($start < $length) {
            $slice = trim(mb_substr($paragraph, $start, $chunkSize));

            if ($slice !== '') {
                $parts[] = $slice;
            }

            if (($start + $chunkSize) >= $length) {
                break;
            }

            $start += max(1, $chunkSize - $overlap);
        }

        return $parts;
    }

    private function tailWithOverlap(string $text, int $overlap): string
    {
        $text = trim($text);

        if ($text === '' || $overlap <= 0) {
            return '';
        }

        $length = mb_strlen($text);

        if ($length <= $overlap) {
            return $text;
        }

        return trim(mb_substr($text, $length - $overlap, $overlap));
    }

    private function makeChunkPayload(string $content, int $index): array
    {
        $content = trim($content);

        return [
            'chunk_index' => $index,
            'content' => $content,
            'content_length' => mb_strlen($content),
            'meta_json' => [
                'char_length' => mb_strlen($content),
                'word_count' => str_word_count(strip_tags($content)),
                'ingestion_version' => 'phase8_complete_v1',
            ],
        ];
    }
}
