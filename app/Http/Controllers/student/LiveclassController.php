<?php

namespace App\Http\Controllers\student;

use App\Http\Controllers\Controller;
use App\Models\LiveClass;
use App\Models\LiveClassParticipant;
use App\Models\Student;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LiveclassController extends Controller
{
    public function index()
    {
        $all_live_classes = LiveClass::with('participants')->whereIn('status', ['Pending', 'Ongoing'])->get();

        $live_class = $all_live_classes->first();

        return view('student.live_class.index', [
            'data' => [
                'live_class' => $live_class,
                'all_live_classes' => $all_live_classes,
            ]
        ]);
    }

    public function join($id)
    {
        $user = Auth::user();
        $student = Student::where('user_id', $user->id)->firstOrFail();
        $class = LiveClass::findOrFail($id);

        if (now()->greaterThan($class->start_time)) {
            return redirect()->back()->with('error', 'The meeting link has expired.');
        }

        LiveClassParticipant::updateOrCreate(
            [
                'live_class_id' => $class->id,
                'student_id' => $student->id,
            ],
            [
                'batch_id' => $class->batch_id,
                'join_time' => now(),
            ]
        );

        return redirect()->away($class->meeting_link);
    }
}
