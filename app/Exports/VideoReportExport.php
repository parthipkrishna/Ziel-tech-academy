<?php

namespace App\Exports;

use App\Models\VideoLog;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class VideoReportExport implements FromCollection, WithHeadings, WithMapping
{
    protected $filters;

    public function __construct($filters = [])
    {
        $this->filters = $filters;
    }

    public function collection()
    {
        $query = VideoLog::query()
            ->select('video_id', 'subject_id', 'subject_session_id')
            ->selectRaw('SUM(duration)/3600 as total_hours') // convert seconds to hours
            ->selectRaw('COUNT(DISTINCT student_id) as total_students');

        if (!empty($this->filters['batch_id'])) {
            $batchId = $this->filters['batch_id'];
            $query->whereHas('student', function ($q) use ($batchId) {
                $q->where('batch_id', $batchId);
            });
        }

        if (!empty($this->filters['subject_id'])) {
            $query->where('subject_id', $this->filters['subject_id']);
        }

        if (!empty($this->filters['session_id'])) {
            $query->where('subject_session_id', $this->filters['session_id']);
        }

        if (!empty($this->filters['date_range'])) {
            $dates = explode(' - ', $this->filters['date_range']);
            if (count($dates) == 2) {
                $start = $dates[0] . ' 00:00:00';
                $end   = $dates[1] . ' 23:59:59';
                $query->whereBetween('created_at', [$start, $end]);
            }
        }

        // Group by video, subject, session
        return $query->groupBy('video_id', 'subject_id', 'subject_session_id')
                     ->with(['video', 'subject', 'session'])
                     ->get();
    }

    public function map($log): array
    {
        return [
            $log->video->title ?? '-',
            $log->subject->name ?? '-',
            $log->session->title ?? '-',
            round($log->total_hours, 2), // show hours rounded
            $log->total_students ?? 0,
        ];
    }

    public function headings(): array
    {
        return [
            'Video Name',
            'Subject Name',
            'Session Name',
            'Total Hours Watched',
            'Total Students Watched',
        ];
    }
}
