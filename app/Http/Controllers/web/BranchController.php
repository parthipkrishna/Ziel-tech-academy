<?php

namespace App\Http\Controllers\web;
use App\Http\Controllers\Controller;
use App\Models\Branch;
use App\Models\WebBanner;
use Illuminate\Http\Request;

class BranchController extends Controller
{
    public function index()
    {
        $data = [
            'banner' => WebBanner::where('type', 'branches')->get(),
            'branches' => Branch::all(),
        ];

        return view('web.branches', $data);
    }
}
