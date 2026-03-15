<?php

namespace App\Exports;

use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithHeadings;

class InstructorOrdersExport implements FromCollection, WithHeadings, ShouldAutoSize
{
    protected array $headers;
    protected array $rows;

    public function __construct(array $headers, array $rows)
    {
        $this->headers = $headers;
        $this->rows = $rows;
    }

    public function headings(): array
    {
        return $this->headers;
    }

    public function collection(): Collection
    {
        return collect($this->rows);
    }
}
