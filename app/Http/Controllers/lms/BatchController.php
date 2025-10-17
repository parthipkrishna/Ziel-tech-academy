<?php

namespace App\Http\Controllers\lms;

use App\Http\Controllers\Controller;
use App\Models\Course;
use Illuminate\Http\Request;
use App\Models\Batch;
use App\Models\User;
use App\Models\UserRole;
use App\Models\QC;
use App\Models\Tutor;
use  App\Models\BatchChannel;
use Illuminate\Support\Facades\Log;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Facades\Validator;
use Exception;
use Illuminate\Support\Facades\DB;

class BatchController extends Controller
{

    public function index()
    {
        $types = BatchChannel::getChannelTypes();
        $batches = Batch::with('batchTutor.batchUser')->get();
        $batchChannelsGrouped = BatchChannel::all()->groupBy('batch_id');
        $qcs = QC::whereHas('user', function ($query) {
            $query->where('status', 1);
        })->with('user:id,name')->get();

        $tutors = Tutor::whereHas('user', function ($query) {
            $query->where('status', 1);
        })->with('user:id,name')->get();
        
        $qc_list = $qcs->map(function ($qc) {
            return [
                'id' => $qc->id,
                'name' => $qc->user->name ?? 'N/A',
            ];
        });

        $tutor_list = $tutors->map(function ($tutor) {
            return [
                'id' => $tutor->id,
                'name' => $tutor->user->name ?? 'N/A',
            ];
        });

        $admin_list = User::where('type', 'lms')
            ->whereHas('roles', function ($q) {
                $q->where('role_name', 'Admin');
            })->with('roles')->get();
        return view('lms.sections.batches.batch', compact('batches','tutor_list','qc_list','types','batchChannelsGrouped'));
    }

    public function create()
    {
        $types = BatchChannel::getChannelTypes();
        $qcs = QC::whereHas('user', function ($query) {
            $query->where('status', 1);
        })->with('user:id,name')->get();

        $tutors = Tutor::whereHas('user', function ($query) {
            $query->where('status', 1);
        })->with('user:id,name')->get();
        $qc_list = $qcs->map(function ($qc) {
            return [
                'id' => $qc->id,
                'name' => $qc->user->name ?? 'N/A',
            ];
        });

        $courses = Course::where('status', '1')->get();

        $tutor_list = $tutors->map(function ($tutor) {
            return [
                'id' => $tutor->id,
                'name' => $tutor->user->name ?? 'N/A',
            ];
        });

        $admin_list = User::where('type', 'lms')
            ->whereHas('roles', function ($q) {
                $q->where('role_name', 'Admin');
            })
            ->with('roles')
            ->get();

        return view('lms.sections.batches.add-batch', compact('tutor_list', 'qc_list', 'admin_list', 'types', 'courses'));
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'batch_number' => 'required|string|unique:batches,batch_number',
            'student_limit' => 'nullable|integer',
            'tutor_id' => 'required|exists:tutors,id',
            'qc_ids' => 'required|array',
            'qc_ids.*' => 'exists:qcs,id',
            'status' => 'required|boolean',
            'channels' => 'nullable|array',
            'channels.*.type' => 'required|string|in:whatsapp,telegram,other',
            'channels.*.group_name' => 'required|string|max:255',
            'channels.*.admin_id' => 'required|exists:users,id',
            'channels.*.batch_status' => 'required|boolean',
            'course_id' => 'required|exists:courses,id',
        ]);

        if ($validator->fails()) {
            // Log the validation errors for debugging purposes
            Log::info('Batch creation validation failed.', ['errors' => $validator->errors()->toArray()]);
            
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        DB::beginTransaction();
        try {
            $validated = $validator->validated();

            $batch = Batch::create([
                'name'              => $validated['name'],
                'batch_number'      => $validated['batch_number'],
                'student_limit'     => $validated['student_limit'] ?? 0,
                'tutor_id'          => $validated['tutor_id'],
                'batch_in_charge_id'=> $validated['tutor_id'],
                'qc_ids'            => json_encode($validated['qc_ids']),
                'status'            => $validated['status'],
                'course_id'         => $validated['course_id'],
            ]);

            if (!empty($validated['channels'])) {
                foreach ($validated['channels'] as $channel) {
                    $admin = User::find($channel['admin_id']);
                    $batch->channels()->create([
                        'type'        => $channel['type'],
                        'group_name'  => $channel['group_name'],
                        'admin_id'    => $channel['admin_id'],
                        'admin_name'  => $admin->name ?? '',
                        'admin_phone' => $admin->phone ?? '',
                        'status'      => $channel['batch_status'],
                    ]);
                }
            }

            DB::commit();
            return response()->json([
                'success' => true,
                'message' => 'Batch created successfully!'
            ]);

        } catch (Exception $e) {
            DB::rollBack();

            // Log the exception for detailed error detection
            Log::error('Error creating batch: ' . $e->getMessage(), ['exception' => $e]);

            return response()->json([
                'success' => false,
                'message' => 'Something went wrong: ' . $e->getMessage()
            ], 500);
        }
    }

    public function edit(string $id)
    {
        $batch = Batch::with(['tutor','channels'])->findOrFail($id);
        $lmsUsers = User::where('type', 'lms')->with('roles')->get();
        $courses = Course::where('status', '1')->get();
        $batchChannelsGrouped = BatchChannel::all()->groupBy('batch_id');
        $types = BatchChannel::getChannelTypes();
        $qcs = QC::all();
        $tutors = Tutor::all();
        $tutor_list = [];
        $qc_list = [];
        $admin_list = [];
        
        foreach ($qcs as $qc) {
            $qUser=User::where('id',$qc->user_id )->first();
            $qc_list[] = [
                'id' => $qc->id,
                'name' => $qUser->name,
            ];
        }

        foreach ($tutors as $tutor) {
            $tUser=User::where('id',$tutor->user_id )->first();
            $tutor_list[] = [
                'id' => $tutor->id,
                'name' => $tUser->name,
            ];
        }

        $admin_list = User::where('type', 'lms')
            ->whereHas('roles', function ($q) {
                $q->where('role_name', 'Admin');
            })
            ->with('roles')
            ->get();
        return view('lms.sections.batches.edit-batch')->with(compact('tutor_list','courses','qc_list','admin_list','types','batch','batchChannelsGrouped'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'batch_number' => "required|string|unique:batches,batch_number,$id",
            'student_limit' => 'nullable|integer',
            'tutor_id' => 'required|exists:tutors,id',
            'qc_ids' => 'required|array',
            'qc_ids.*' => 'exists:qcs,id',
            'status' => 'required|boolean',
            'channels' => 'nullable|array',
            'channels.*.type' => 'required|string|in:whatsapp,telegram,other',
            'channels.*.group_name' => 'required|string|max:255',
            'channels.*.admin_id' => 'required|exists:users,id',
            'channels.*.batch_status' => 'required|boolean',
            'course_id' => 'required',
        ]);

        \DB::beginTransaction();
        try {
            $batch = Batch::findOrFail($id);
            $batch->update([
                'name' => $validated['name'],
                'batch_number' => $validated['batch_number'],
                'student_limit' => $validated['student_limit'] ?? 0,
                'tutor_id' => $validated['tutor_id'],
                'batch_in_charge_id' => $validated['tutor_id'],
                'qc_ids' => json_encode($validated['qc_ids']),
                'status' => $validated['status'],
                'course_id' => $validated['course_id'] ?? null,
            ]);

            if (!empty($validated['channels'])) {
                foreach ($validated['channels'] as $channel) {
                    $admin = User::find($channel['admin_id']);
                    $batch->channels()->create([
                        'type' => $channel['type'],
                        'group_name' => $channel['group_name'],
                        'admin_id' => $channel['admin_id'],
                        'admin_name' => $admin->name ?? '',
                        'admin_phone' => $admin->phone ?? '',
                        'status' => $channel['batch_status'],
                    ]);
                }
            }

            \DB::commit();
            return redirect()->route('lms.batches')->with('message', 'Batch created successfully!');

        } catch (\Exception $e) {
            \DB::rollBack();
            return redirect()->back()->withErrors(['error' => 'Something went wrong: ' . $e->getMessage()]);
        }
    }
    public function updateStatus(Request $request, $id)
    {
        $request->validate([
            'status' => 'required|boolean',
        ]);

        $batch = Batch::findOrFail($id);
        $batch->update(['status' => $request->status]);

        return response()->json(['message' => 'Status updated successfully']);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $batch = Batch::findOrFail($id);
        \DB::beginTransaction();
        try {
            $batch->channels()->delete();
            $batch->delete();
            \DB::commit();
            return redirect()->back()->with('message', 'Batch deleted successfully!');
        } catch (\Exception $e) {
            \DB::rollBack();
            return redirect()->back()->withErrors(['error' => 'Failed to delete batch: ' . $e->getMessage()]);
        }
    }

    public function deleteBatchChannel(Request $request, $id)    {
        $channel = BatchChannel::findOrFail($id);
        $channel->delete();
        if ($request->ajax()) {
            return response()->json(['success' => true, 'id' => $id]);
        }
        return redirect()->back()->with('success', 'Batch channel deleted successfully.');    
    }
    public function ajaxList(Request $request)
    {
        $batches = Batch::with(['batchTutor.batchUser', 'course'])->latest();

        return DataTables::of($batches)
            ->addColumn('tutor', function ($batch) {
                return $batch->batchTutor->batchUser->name ?? 'N/A';
            })
            ->addColumn('course_name', function ($batch) {
                    return $batch->course->name ?? '-';
                })
            ->addColumn('status', function ($batch) {
                $checked = $batch->status ? 'checked' : '';
                return '
                    <div>
                        <input type="checkbox" class="status-toggle" data-id="' . $batch->id . '" id="switch' . $batch->id . '" ' . $checked . ' data-switch="success">
                        <label for="switch' . $batch->id . '" data-on-label="Yes" data-off-label="No" class="mb-0 d-block"></label>
                    </div>';
            })
            ->addColumn('action', function ($batch) {
                $edit = '';
                $delete = '';

                if (auth()->user()->hasPermission('batches.update')) {
                    $editUrl = route('lms.edit.batch', $batch->id);
                    $edit = '<a href="' . $editUrl . '" class="action-icon"><i class="mdi mdi-square-edit-outline"></i></a>';
                }

                if (auth()->user()->hasPermission('batches.delete')) {
                    $delete = '
                    <a href="javascript:void(0);" class="action-icon btn-delete-batch" data-id="' . $batch->id . '">
                        <i class="mdi mdi-delete"></i>
                    </a>';
                }

                return $edit . ' ' . $delete;
            })
            ->rawColumns(['status', 'action'])
            ->make(true);
    }
}
