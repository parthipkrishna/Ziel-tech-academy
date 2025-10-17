<?php

namespace App\Http\Controllers\student;

use App\Http\Controllers\Controller;
use App\Models\Student;
use App\Models\Subscription;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SubscriptionController extends Controller
{
    public function index()
    {
       try {
        $user = Auth::user();

        $student = Student::where('user_id', $user->id)->firstOrFail();

        $subscriptions = Subscription::where('student_id', $student->id)
                        ->with('course') // make sure relationship exists
                        ->orderByDesc('start_date')
                        ->get();

        return view('student.subscription.subscription', compact('subscriptions'));
    } catch (\Exception $e) {
        return response()->json(['error' => 'Failed to load subscriptions: ' . $e->getMessage()], 500);
    }
}
}
