<?php

namespace App\Http\Controllers\API\V1;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\Notification;
use App\Models\Student;
use Illuminate\Support\Facades\Auth;

class NotificationController extends Controller
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
     * Get all notifications for the authenticated student with pagination & search.
     */
    public function index(Request $request)
    {
        try {
            $authUser = Auth::user();
            $studentId = $authUser->studentId;

            $perPage = (int) $request->query('per_page', 10);
            $page = (int) $request->query('page', 1);

            // 1) Get student batches
            $student = Student::with('batches')->findOrFail($studentId);
            $batchIds = $student->batches->pluck('id')->toArray();

            // 2) Collect notifications
            $query = Notification::query()
                ->where(function ($q) use ($studentId, $batchIds) {
                    $q->where('category_type', 'general') // all general
                        ->orWhere(function ($q2) use ($studentId) { // student-specific
                            $q2->where('category_type', 'student')
                                ->whereJsonContains('student_ids', $studentId);
                        })
                        ->orWhere(function ($q3) use ($batchIds) { // batch notifications
                            if (!empty($batchIds)) {
                                foreach ($batchIds as $batchId) {
                                    $q3->orWhereJsonContains('batch_ids', $batchId);
                                }
                            }
                        });
                })
                ->orderBy('created_at', 'desc');

            // 3) Pagination
            $notifications = $query->paginate($perPage, ['*'], 'page', $page);

            // 4) Group by date
            $grouped = $notifications->getCollection()->groupBy(function ($item) {
                $date = $item->created_at->startOfDay();
                $today = now()->startOfDay();
                $yesterday = now()->subDay()->startOfDay();

                if ($date->equalTo($today)) {
                    return 'Today';
                } elseif ($date->equalTo($yesterday)) {
                    return 'Yesterday';
                } else {
                    return $date->format('d F Y');
                }
            });

            // 5) Transform into sections
            $sections = [];
            foreach ($grouped as $title => $items) {
                $sections[] = [
                    'title' => $title,
                    'items' => $items->values(), // reset array keys
                ];
            }

            return response()->json([
                'status' => true,
                'data'   => [
                    'current_page' => $notifications->currentPage(),
                    'per_page'     => $notifications->perPage(),
                    'total'        => $notifications->total(),
                    'last_page'    => $notifications->lastPage(),
                    'data'     => $sections,
                ],
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'error'  => 'Failed to fetch notifications: ' . $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Store a new notification.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'title'         => 'required|string|max:255',
            'body'          => 'required|string',
            'type'          => 'required|in:' . implode(',', array_keys(Notification::getTypes())),
            'category_type' => 'required|in:' . implode(',', array_keys(Notification::getCategoryTypes())),
            'image'         => 'nullable|url',
            'link'          => 'nullable|url',
            'student_ids'   => 'nullable|array',
            'batch_ids'     => 'nullable|array',
            'extra_info'    => 'nullable|array',
            'user_id'       => 'nullable|exists:users,id',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'error'  => $validator->errors()->first(),
            ], 422);
        }

        try {
            $data = [
                'user_id'        => $request->user_id ?? Auth::id(),
                'title'          => $request->title,
                'body'           => $request->body,
                'type'           => $request->type,
                'category_type'  => $request->category_type,
                'image'          => $request->image ?? null,
                'link'           => $request->link ?? null,
                'extra_info'     => $request->extra_info ?? [],
                'status'         => 'active',
                'delivered_count' => 0,
            ];

            // Handle category-specific fields
            if ($request->category_type === 'student') {
                $data['student_ids'] = $request->student_ids ?? [];
            } elseif ($request->category_type === 'batch') {
                $data['batch_ids'] = $request->batch_ids ?? [];
            } else { // general
                $data['student_ids'] = [];
                $data['batch_ids'] = [];
            }

            $notification = Notification::create($data);

            return response()->json([
                'status'       => true,
                'message'      => 'Notification created successfully',
                'notification' => $notification,
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'error'  => 'Failed to create notification: ' . $e->getMessage(),
            ], 500);
        }
    }
}
