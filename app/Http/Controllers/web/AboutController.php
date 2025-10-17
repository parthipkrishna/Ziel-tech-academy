<?php

namespace App\Http\Controllers\web;

use App\Http\Controllers\Controller;
use App\Models\CompanyInfo;
use App\Models\WebBanner;
use Illuminate\Http\Request;

class AboutController extends Controller
{
    public function index()
    {
        $data = [
            'banner' => WebBanner::where('type', 'about us')->get(),
            'info' => CompanyInfo::all(),
        ];
        
        return view('web.about', $data);
    }
}
