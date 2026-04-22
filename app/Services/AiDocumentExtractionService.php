<?php

namespace App\Services;

use App\Models\AiDocument;
use Illuminate\Support\Facades\Storage;
use RuntimeException;
use Smalot\PdfParser\Parser as PdfParser;
use ZipArchive;
use DOMDocument;
use DOMXPath;

class AiDocumentExtractionService
{
    public function extract(AiDocument $document): string
    {
        if (!empty($document->extracted_text) && $document->source_type === 'manual_upload') {
            return trim((string) $document->extracted_text);
        }

        if (!$document->storage_disk || !$document->storage_path) {
            return trim((string) $document->extracted_text);
        }

        $localPath = $this->materializeToTempFile($document->storage_disk, $document->storage_path);

        try {
            $extension = strtolower(pathinfo($document->file_name ?: $document->storage_path, PATHINFO_EXTENSION));

            return match ($extension) {
                'pdf' => $this->extractFromPdf($localPath),
                'docx' => $this->extractFromDocx($localPath),
                'txt', 'md' => $this->extractFromPlainText($localPath),
                default => throw new RuntimeException("Chưa hỗ trợ extract cho định dạng: {$extension}"),
            };
        } finally {
            if (is_file($localPath)) {
                @unlink($localPath);
            }
        }
    }

    private function materializeToTempFile(string $disk, string $path): string
    {
        $contents = Storage::disk($disk)->get($path);
        $extension = pathinfo($path, PATHINFO_EXTENSION) ?: 'tmp';
        $tempPath = tempnam(sys_get_temp_dir(), 'ai_doc_');

        if ($tempPath === false) {
            throw new RuntimeException('Không tạo được file tạm để extract tài liệu.');
        }

        $targetPath = $tempPath . '.' . $extension;
        rename($tempPath, $targetPath);
        file_put_contents($targetPath, $contents);

        return $targetPath;
    }

    private function extractFromPdf(string $path): string
    {
        $parser = new PdfParser();
        $pdf = $parser->parseFile($path);

        return $this->normalizeExtractedText($pdf->getText());
    }

    private function extractFromDocx(string $path): string
    {
        $zip = new ZipArchive();

        if ($zip->open($path) !== true) {
            throw new RuntimeException('Không thể mở file DOCX.');
        }

        $xml = $zip->getFromName('word/document.xml');
        $zip->close();

        if ($xml === false) {
            throw new RuntimeException('Không tìm thấy word/document.xml trong file DOCX.');
        }

        $dom = new DOMDocument();
        $dom->loadXML($xml);

        $xpath = new DOMXPath($dom);
        $xpath->registerNamespace('w', 'http://schemas.openxmlformats.org/wordprocessingml/2006/main');

        $paragraphs = [];
        foreach ($xpath->query('//w:p') as $paragraphNode) {
            $texts = [];
            foreach ($xpath->query('.//w:t', $paragraphNode) as $textNode) {
                $texts[] = $textNode->textContent;
            }

            $paragraph = trim(implode('', $texts));
            if ($paragraph !== '') {
                $paragraphs[] = $paragraph;
            }
        }

        return $this->normalizeExtractedText(implode("\n\n", $paragraphs));
    }

    private function extractFromPlainText(string $path): string
    {
        $content = file_get_contents($path);

        if ($content === false) {
            throw new RuntimeException('Không đọc được file text.');
        }

        return $this->normalizeExtractedText($content);
    }

    private function normalizeExtractedText(string $text): string
    {
        $text = str_replace(["\r\n", "\r"], "\n", $text);
        $text = preg_replace('/[ \t]+/u', ' ', $text);
        $text = preg_replace('/\n{3,}/u', "\n\n", $text);

        return trim((string) $text);
    }
}
