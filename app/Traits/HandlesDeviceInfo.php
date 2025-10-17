<?php

namespace App\Traits;

use Illuminate\Http\Request;
use App\Models\StudentDeviceInfo;


trait HandlesDeviceInfo
{
    public function handleStudentDevice($user, Request $request)
    {
        // Skip if no device ID provided
        if (!$request->device_id) return;

        $studentId = $user->studentProfile?->id;
        if (!$studentId) return;

        // Check if this device is already registered
        $existingDevice = StudentDeviceInfo::where('student_id', $studentId)
            ->where('device_id', $request->device_id)
            ->where('is_approved', true)
            ->first();

        // Already approved or previously registered
        if ($existingDevice) return;

        // Count only approved devices
        $approvedCount = StudentDeviceInfo::where('student_id', $studentId)
            ->where('is_approved', true)
            ->count();

        // Create device info
        StudentDeviceInfo::create([
            'student_id'  => $studentId,
            'device_id'   => $request->device_id,
            'device_type' => $request->device_type,
            'device_name' => $request->device_name,
            'ip_address'  => $request->ip(),
            'browser'     => $request->userAgent(),
            'is_approved' => $approvedCount < 2, // Approve first 2 devices only
        ]);

        // Update blocking logic
        if ($approvedCount >= 2) {
            $user->studentProfile->update([
                'is_device_blocked' => true
            ]);
        }
    }
}
