<?php

namespace App\Http\Controllers\API\V1;

use App\Http\Controllers\Controller;
use App\Models\Course;
use App\Models\Student;
use App\Models\StudentEnrollment;
use App\Models\CertificateToken;
use Barryvdh\DomPDF\Facade\Pdf as FacadePdf;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class CertificateController extends Controller
{
    private string $baseDir = 'private'; // single private folder
    /**
     * Validate if student is eligible for certificate
     */
    private function validateEligibility(int $studentId, int $courseId)
    {
        $student = Student::find($studentId);
        if (!$student) {
            throw new \Exception('Student not found.', 404);
        }

        $course = Course::find($courseId);
        if (!$course) {
            throw new \Exception('Course not found.', 404);
        }

        $enrollment = StudentEnrollment::where('student_id', $studentId)
            ->where('course_id', $courseId)
            ->first();

        if (!$enrollment || $enrollment->status !== 'completed') {
            throw new \Exception('Certificate not available for this student.', 403);
        }

        return [$student, $course];
    }

    /**
     * List all completed certificates for a student
     */
    public function list(int $studentId)
    {
        try {
            $student = Student::find($studentId);
            if (!$student) {
                return response()->json([
                    'status' => false,
                    'message' => 'Student not found.'
                ], 404);
            }

            $enrollments = StudentEnrollment::with('course:id,title')
                ->where('student_id', $student->id)
                ->where('status', 'completed')
                ->get();

            $certificates = $enrollments->map(function ($enrollment) use ($student) {
                $studentDir = "{$this->baseDir}/student/{$student->id}";
                $filename = "certificate_{$student->id}_{$enrollment->course->id}.pdf";
                $filePath = "{$studentDir}/{$filename}";

                return [
                    'course_id' => $enrollment->course->id,
                    'course_title' => $enrollment->course->title,
                    'certificate_exists' => Storage::disk('local')->exists($filePath),
                    'completed_at' => $enrollment->updated_at->format('d M Y'),
                ];
            });

            Log::info("Listed certificates for Student ID: {$student->id}");
            return response()->json([
                'status' => true,
                'message' => 'Certificates fetched successfully',
                'data' => $certificates
            ], 200);
        } catch (\Exception $e) {
            Log::error("Error listing certificates: " . $e->getMessage());
            $statusCode = in_array($e->getCode(), [403, 404, 400]) ? $e->getCode() : 500;
            return response()->json(['status' => false, 'message' => $e->getMessage()], $statusCode);
        }
    }

    /**
     * Generate certificate and return temporary URL
     */
    public function generate(int $studentId, int $courseId)
    {
        try {
            [$student, $course] = $this->validateEligibility($studentId, $courseId);

            // Ensure single private folder
            $studentDir = "/student/{$student->id}";
            if (!Storage::disk('local')->exists($studentDir)) {
                Storage::disk('local')->makeDirectory($studentDir, 0777, true);
            }

            $filename = "certificate_{$student->id}_{$course->id}.pdf";
            $filePath = "{$studentDir}/{$filename}";

            // Delete old certificate if exists
            if (Storage::disk('local')->exists($filePath)) {
                Storage::disk('local')->delete($filePath);
                Log::info("Old certificate deleted: Student {$student->id}, Course {$course->id}");
            }

            // Generate PDF
            $pdf = FacadePdf::loadView('certificates.student_certificate', [
                'student' => $student,
                'course' => $course,
                'date' => now()->format('d M Y'),
            ])->output();

            Storage::disk('local')->put($filePath, $pdf);
            Log::info("Certificate generated: Student {$student->id}, Course {$course->id}");

            // Create temporary token (1 minute)
            $token = Str::random(50);
            CertificateToken::updateOrCreate(
                ['student_id' => $student->id, 'course_id' => $course->id],
                ['token' => $token, 'expires_at' => now()->addMinutes(5)]
            );

            // Return URL for template usage
            $url = route('certificates.serve', ['filename' => $filename, 'token' => $token]);

            return response()->json([
                'status' => true,
                'message' => 'Certificate ready',
                'certificate_url' => $url,
                'expires_in' => 60
            ], 200);
        } catch (\Exception $e) {
            Log::error("Error generating certificate: " . $e->getMessage());
            $statusCode = in_array($e->getCode(), [403, 404, 400]) ? $e->getCode() : 500;
            return response()->json(['status' => false, 'message' => $e->getMessage()], $statusCode);
        }
    }

    /**
     * Serve certificate if token is valid
     */
    public function serveTemporary($filename)
    {
        try {
            $token = request()->query('token');
            if (!$token) {
                return response()->json(['status' => false, 'message' => 'Token required'], 403);
            }

            if (!preg_match('/certificate_(\d+)_(\d+)\.pdf$/', $filename, $matches)) {
                return response()->json(['status' => false, 'message' => 'Invalid file name'], 400);
            }

            [$full, $studentId, $courseId] = $matches;
            $filePath = "student/{$studentId}/{$filename}";

            if (!Storage::disk('local')->exists($filePath)) {
                return response()->json(['status' => false, 'message' => 'Certificate not found'], 404);
            }

            $record = CertificateToken::where('student_id', $studentId)
                ->where('course_id', $courseId)
                ->where('token', $token)
                ->first();

            // ❌ Invalid or expired
            if (!$record || now()->gt($record->expires_at)) {
                if (Storage::disk('local')->exists($filePath)) {
                    Storage::disk('local')->delete($filePath);
                    Log::info("Expired certificate deleted: Student {$studentId}, Course {$courseId}");
                }
                if ($record) $record->delete();

                return response()->json(['status' => false, 'message' => 'Token expired or invalid'], 403);
            }

            // ✅ Valid token: allow multiple uses until expiry
            return response()->file(storage_path("app/private/{$filePath}"));
        } catch (\Exception $e) {
            Log::error("Error serving certificate: " . $e->getMessage());
            $statusCode = in_array($e->getCode(), [403, 404, 400]) ? $e->getCode() : 500;
            return response()->json(['status' => false, 'message' => $e->getMessage()], $statusCode);
        }
    }
}
