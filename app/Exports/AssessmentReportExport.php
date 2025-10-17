<?php

namespace App\Exports;

use App\Models\Exam;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Illuminate\Http\Request;

class AssessmentReportExport implements FromCollection, WithHeadings, WithMapping
{
    protected $filters;

    public function __construct($filters = [])
    {
        $this->filters = $filters;
    }

    public function collection()
    {
        $query = Exam::with('subject')
            ->where('type', 'Assessment')
            ->withCount([
                'attempts as total_passed' => fn($q) => $q->where('status', 'Passed'),
                'attempts as total_failed' => fn($q) => $q->where('status', 'Failed'),
            ]);

        // Batch filter
        if (!empty($this->filters['batch_id'])) {
            $query->where('batch_id', $this->filters['batch_id']);
        }

        // Subject filter
        if (!empty($this->filters['subject_id'])) {
            $query->where('subject_id', $this->filters['subject_id']);
        }

        // Date range filter
        if (!empty($this->filters['date_range'])) {
            $dates = explode(' - ', $this->filters['date_range']);
            if(count($dates) == 2) {
                $start = $dates[0] . ' 00:00:00';
                $end   = $dates[1] . ' 23:59:59';
                $query->whereBetween('created_at', [$start, $end]);
            }
        }

        return $query->get();
    }

    // Map data for Excel
    public function map($exam): array
    {
        return [
            $exam->name,
            $exam->subject->name ?? '-',
            $exam->subjectSession->title ?? '-',
            (string) ($exam->total_passed ?? 0),
            (string) ($exam->total_failed ?? 0),
        ];
    }

    // Column headings
    public function headings(): array
    {
        return [
            'Assessment Name',
            'Subject',
            'Session',
            'Total Passed',
            'Total Failed',
        ];
    }
}
