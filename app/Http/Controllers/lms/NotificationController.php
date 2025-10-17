<?php

namespace App\Http\Controllers\lms;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Notification;
use App\Models\User;
use App\Models\Student;
use App\Models\Batch;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\Facades\DataTables;
class NotificationController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
          return view('lms.sections.notification.notification');
    }


    public function getNotifications(Request $request)
    {
        $notifications = Notification::select('id', 'title', 'body', 'category_type', 'delivered_count', 'created_at', 'status')->latest();

        return DataTables::of($notifications)
            ->editColumn('body', function ($row) {
                return \Str::limit($row->body, 25, '...');
            })
            ->editColumn('delivered_count', function ($row) {
                return $row->delivered_count ?? '<span class="small text-danger">Not Available</span>';
            })
            ->editColumn('created_at', function ($row) {
                return $row->created_at->format('d M Y');
            })
            ->editColumn('status', function ($row) {
                return match ($row->status) {
                    'Processing' => '<span class="badge bg-warning">Processing</span>',
                    'Delivered' => '<span class="badge bg-success">Delivered</span>',
                    'Failed' => '<span class="badge bg-danger">Failed</span>',
                    default => '<span class="badge bg-secondary">Unknown</span>',
                };
            })
            ->rawColumns(['delivered_count', 'status'])
            ->make(true);
    }

    
    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $students = Student::all();
        $batches = Batch::all();
        return view('lms.sections.notification.add', ['types' => Notification::getTypes(),'categoryTypes' => Notification::getCategoryTypes(),'students' => $students,'batches' => $batches,]);
    }

    /**
     * Store a newly created resource in storage.
     */

    public function store(Request $request)
    {
        Log::info('Notification store request started', $request->all());
    
        try {
            // Validation
            $request->validate([
                'title' => 'required|min:3',
                'link' => 'nullable|url',
                'type' => 'required|string',
                'body' => 'required|min:5',
                'category_type' => 'required',
                'student_ids' => 'nullable|array',
                'batch_ids' => 'nullable|array',
            ]);
    
            $profilePath = null;
            if ($request->hasFile('image')) {
                $profilePath = $request->file('image')->store('uploads/images/Notification', 'public');
                Log::info('Notification image stored at: ' . $profilePath);
            }
    
            // Start DB transaction
            DB::beginTransaction();
    
            $notification = new Notification();
            $notification->user_id = auth()->id();
            $notification->title = $request->title;
            $notification->body = $request->body;
            $notification->image = $profilePath;
            $notification->link = $request->link ? $request->link : NULL;
            $notification->type = $request->type;
            $notification->category_type = $request->category_type;
            $notification->extra_info = $request->extra_info;
            $notification->student_ids = $request->student_ids ? json_encode($request->student_ids) : null;
            $notification->batch_ids = $request->batch_ids ? json_encode($request->batch_ids) : null;
            $notification->status = "Processing";
            $notification->delivered_count = $request->delivered_count ?? null;
            $notification->save();
            DB::commit();
    
            Log::info('Notification saved successfully', ['id' => $notification->id]);
    
            return redirect()->route('lms.notifications')->with('message', 'Notification created successfully');
    
        } catch (\Illuminate\Validation\ValidationException $e) {
            Log::error('Validation failed', ['errors' => $e->errors()]);
            return back()->withErrors($e->errors())->withInput();
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error storing notification', ['error' => $e->getMessage()]);
            return back()->with('error', 'Failed to create notification')->withInput();
        }
    }
}
