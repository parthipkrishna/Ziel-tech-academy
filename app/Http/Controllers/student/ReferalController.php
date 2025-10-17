<?php

namespace App\Http\Controllers\student;

use App\Http\Controllers\Controller;
use App\Models\ReferralCode;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ReferalController extends Controller
{

public function index()
{
    $user = Auth::user();

    $referralCode = ReferralCode::where('generated_by', $user->id)
                    ->where('is_active', true)
                    ->first();

    return view('student.referals.refer-earn', compact('referralCode'));
}

}
