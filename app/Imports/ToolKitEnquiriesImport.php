<?php

namespace App\Imports;

use App\Models\ToolKitEnquiry;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class ToolKitEnquiriesImport implements ToModel, WithHeadingRow
{
    public function model(array $row)
    {
        // Update only existing enquiry by ID
        return ToolKitEnquiry::updateOrCreate(
            ['id' => $row['id']], // match by primary key
            [
                'status'       => $row['status'] ?? null,
                'total_amount' => $row['total_amount'] ?? null,
                'address'      => $row['address'] ?? null,
            ]
        );
    }
    public function uniqueBy()
    {
        // Tell the package to use the 'id' column for identifying unique records.
        return 'id';
    }

    public function rules(): array
    {
        return [
            '*.id'          => 'required|integer', // 'exists' is not needed with upserts
            '*.status'      => 'nullable|in:request_placed,cancelled,delivered',
            '*.total_amount' => 'nullable|numeric|min:0',
            '*.email'       => 'nullable|email',
            '*.phone'       => 'nullable|string',
        ];
    }

    public function batchSize(): int
    {
        return 500;
    }
}
