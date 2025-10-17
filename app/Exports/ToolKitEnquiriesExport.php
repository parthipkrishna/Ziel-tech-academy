<?php

namespace App\Exports;

use App\Models\ToolKitEnquiry;
use Illuminate\Support\Facades\Log;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithColumnFormatting;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;

class ToolKitEnquiriesExport implements FromQuery, WithHeadings, WithMapping, WithColumnFormatting, ShouldAutoSize
{
    protected $filters;

    public function __construct(array $filters = [])
    {
        $this->filters = $filters;
    }

    public function query()
    {
        $query = ToolkitEnquiry::query();

        // Filters
        if (!empty($this->filters['status'])) {
            $query->where('status', $this->filters['status']);
        }
        if (!empty($this->filters['search'])) {
            $search = $this->filters['search'];
            $query->where(function ($q) use ($search) {
                $q->where('student_name', 'like', "%$search%")
                  ->orWhere('email', 'like', "%$search%")
                  ->orWhere('phone', 'like', "%$search%");
            });
        }

        if (!empty($this->filters['batch_id'])) {
            $batchId = $this->filters['batch_id'];
            $query->whereHas('student', function ($q) use ($batchId) {
                $q->whereHas('batches', function ($batchQ) use ($batchId) {
                    $batchQ->where('batches.id', $batchId);
                });
            });
        }

        if (!empty($this->filters['date_range'])) {
            $dates = explode(' - ', $this->filters['date_range']);
            if (count($dates) === 2) {
                $startDate = \Carbon\Carbon::parse($dates[0])->startOfDay();
                $endDate   = \Carbon\Carbon::parse($dates[1])->endOfDay();
                $query->whereBetween('created_at', [$startDate, $endDate]);
            }
        }

        Log::channel('prologger')->info('ToolkitEnquiriesExport query built', [
            'filters' => $this->filters
        ]);

        return $query->orderByDesc('id');
    }

    public function headings(): array
    {
        return [
            'ID',
            'Student Name',
            'Email',
            'Phone',
            'State',
            'Address',
            'Toolkit Name',
            'Total Amount',
            'Status',
        ];
    }

    public function map($enquiry): array
    {
        return [
            $enquiry->id,
            $enquiry->student_name,
            $enquiry->email,
            $enquiry->phone,
            $enquiry->state,
            $enquiry->address,
            $enquiry->toolkit_name,
            $enquiry->total_amount,
            $enquiry->status,
        ];
    }

    public function columnFormats(): array
    {
        return [
            'G' => NumberFormat::FORMAT_NUMBER_00,
            'I' => NumberFormat::FORMAT_DATE_DATETIME,
            'J' => NumberFormat::FORMAT_DATE_DATETIME,
        ];
    }
}
