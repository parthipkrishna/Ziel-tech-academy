<?php

namespace App\Http\Controllers\API\V1;

use App\Http\Controllers\Controller;
use App\Models\CourseSection;
use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Exception;
use Illuminate\Http\JsonResponse;

class CourseSectionController extends Controller
{
    /**
     * Middleware for authentication.
     */
    public function __construct()
    {
        $this->middleware(function ($request, $next) {
            return $next($request);
        });
    }

    /**
     * Display a listing of all course sections.
     */
    public function index(): JsonResponse
    {
        try {
            // Fetch all course sections
            $courseSections = CourseSection::all();

            if ($courseSections->isEmpty()) {
                return response()->json([
                    'status' => false,
                    'message' => 'No course sections found.'
                ], 404);
            }

            return response()->json([
                'status' => true,
                'data' => $courseSections
            ], 200);
        } catch (Exception $e) {
            return response()->json([
                'status' => false,
                'error' => 'Failed to fetch course sections: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Store a newly created course section.
     */
    public function store(Request $request): JsonResponse
    {
        try {
            // Validate the incoming request data for the course section
            $validatedData = $request->validate([
                'name' => 'required|string|max:255',
                'status' => 'required|boolean',
            ]);

            // Create the course section
            $courseSection = CourseSection::create([
                'name' => $validatedData['name'],
                'status' => $validatedData['status'],
            ]);

            return response()->json([
                'status' => true,
                'message' => 'Course section created successfully',
                'data' => $courseSection
            ], 201);
        } catch (Exception $e) {
            return response()->json([
                'status' => false,
                'error' => 'Course section creation failed: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Display the specified course section.
     */
    public function show($sectionId): JsonResponse
    {
        try {
            // Find the course section by its ID
            $courseSection = CourseSection::findOrFail($sectionId);

            return response()->json([
                'status' => true,
                'data' => $courseSection
            ], 200);
        } catch (ModelNotFoundException $e) {
            return response()->json([
                'status' => false,
                'error' => 'Course section not found'
            ], 404);
        } catch (Exception $e) {
            return response()->json([
                'status' => false,
                'error' => 'Failed to retrieve course section: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Update the specified course section.
     */
    public function update(Request $request, $sectionId): JsonResponse
    {
        try {
            // Find the course section by its ID
            $courseSection = CourseSection::findOrFail($sectionId);

            // Validate the incoming request data for the course section
            $validatedData = $request->validate([
                'name' => 'required|string|max:255',
                'status' => 'required|boolean',
            ]);

            // Update the course section with the new data
            $courseSection->update($validatedData);

            return response()->json([
                'status' => true,
                'message' => 'Course section updated successfully',
                'data' => $courseSection
            ], 200);
        } catch (ModelNotFoundException $e) {
            return response()->json([
                'status' => false,
                'error' => 'Course section not found'
            ], 404);
        } catch (Exception $e) {
            return response()->json([
                'status' => false,
                'error' => 'Update failed: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Remove the specified course section.
     */
    public function destroy($sectionId): JsonResponse
    {
        try {
            // Find the course section by its ID
            $courseSection = CourseSection::findOrFail($sectionId);

            // Delete the course section
            $courseSection->delete();

            return response()->json([
                'status' => true,
                'message' => 'Course section deleted successfully'
            ], 200);
        } catch (ModelNotFoundException $e) {
            return response()->json([
                'status' => false,
                'error' => 'Course section not found'
            ], 404);
        } catch (Exception $e) {
            return response()->json([
                'status' => false,
                'error' => 'Deletion failed: ' . $e->getMessage()
            ], 500);
        }
    }
}
