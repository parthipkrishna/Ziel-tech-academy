<?php

namespace App\Exports;

use App\Models\LiveClass;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class LiveClassReportExport implements FromCollection, WithHeadings, WithMapping
{
    protected $filters;

    public function __construct($filters = [])
    {
        $this->filters = $filters;
    }

    public function collection()
    {
        $query = LiveClass::with(['batch', 'tutor', 'participants']);

        // Apply batch filter
        if (!empty($this->filters['batch_id'])) {
            $query->where('batch_id', $this->filters['batch_id']);
        }

        // Apply tutor filter
        if (!empty($this->filters['tutor_id'])) {
            $query->where('tutor_id', $this->filters['tutor_id']);
        }

        // Apply date range filter
        if (!empty($this->filters['date_range'])) {
            $dates = explode(' - ', $this->filters['date_range']);
            if (count($dates) === 2) {
                $query->whereBetween('start_time', [$dates[0] . ' 00:00:00', $dates[1] . ' 23:59:59']);
            }
        }

        return $query->get();
    }

    // Map data for Excel
    public function map($liveClass): array
    {
        return [
            $liveClass->name,
            $liveClass->batch->name ?? '-',
            $liveClass->participants->count(),
            ($liveClass->start_time ? $liveClass->start_time->format('d-M-Y H:i') : '-') . ' - ' . 
            ($liveClass->end_time ? $liveClass->end_time->format('H:i') : '-'),
            $liveClass->tutor->user->name ?? '-',
        ];
    }

    // Column headings
    public function headings(): array
    {
        return [
            'Name',
            'Batch Name',
            'Total Students Participated',
            'Date & Time',
            'Faculty Name',
        ];
    }
}
