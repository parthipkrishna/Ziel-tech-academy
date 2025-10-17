<?php

namespace App\Http\Controllers\web;
use App\Http\Controllers\Controller;
use App\Models\Placement;
use App\Models\WebBanner;
use Illuminate\Http\Request;

class PlacementController extends Controller
{
    public function index()
    {
        $data = [
            'banner' => WebBanner::where('type', 'placement')->get(),
            'placement' => Placement::all(),
        ];
        return view('web.placement', $data);
    }
}
