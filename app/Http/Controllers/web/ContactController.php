<?php

namespace App\Http\Controllers\web;

use App\Http\Controllers\Controller;
use App\Models\ContactInfo;
use App\Models\WebBanner;
use Illuminate\Http\Request;

class ContactController extends Controller
{
    public function index()
    {   $banner = WebBanner::where('type', 'contact us')->get();
        $contact = ContactInfo::all();
        return view('web.contact',compact('banner', 'contact'));
    }
}
