<?php

namespace App\Http\Controllers\API\V1;

use App\Http\Controllers\Controller;
use App\Models\Banner;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\Validator;

class BannerController extends Controller
{
    public function __construct()
    {
        $this->middleware(function ($request, $next) {
            if ($request->route()->getActionMethod() !== 'index') {
                // Assuming you need to use the authenticated user's id for non-index methods
                $this->userId = auth()->user()->user_id;
            }
            return $next($request);
        });
    }

    public function index(Request $request): JsonResponse
    {
        $banners = Banner::get(); // Retrieve all banners

        if ($banners->isEmpty()) {
            return response()->json([
                'status' => true,
                'message' => 'No banners found',
            ], 200);
        }

        return response()->json([
            'status' => true,
            'data' => $banners,
        ], 200);
    }

    public function store(Request $request): JsonResponse
    {
        try {
            $validator = Validator::make($request->all(), [
                'image'             => 'required|file|mimes:jpeg,png,jpg,gif|max:2048', // Max 2MB
                'type'              => 'required|in:course,toolkit',
                'related_id'        => 'required|integer',
                'short_description' => 'nullable|string',
            ]);
    
            if ($validator->fails()) {
                return response()->json([
                    'status'  => false,
                    'message' => $validator->errors()->first(),
                ], 400);
            }
    
            // Store the image in the 'public/images/banners' directory
            $imagePath = $request->file('image')->store('images/banners', 'public');
    
            $banner = Banner::create([
                'image'             => $imagePath, // Store the image path
                'type'              => $request->input('type'),
                'related_id'        => $request->input('related_id'),
                'short_description' => $request->input('short_description'),
            ]);
    
            return response()->json([
                'status'  => true,
                'message' => 'Banner added successfully',
                'data'    => $banner,
            ], 200);
        } catch (QueryException $e) {
            return response()->json([
                'status'  => false,
                'message' => 'Database query error: ' . $e->getMessage(),
            ], 500);
        } catch (\Exception $e) {
            return response()->json([
                'status'  => false,
                'message' => 'An error occurred: ' . $e->getMessage(),
            ], 500);
        }
    }


    public function show($id): JsonResponse
    {
        $banner = Banner::find($id);

        if (!$banner) {
            return response()->json([
                'status'  => false,
                'message' => 'Banner not found',
            ], 404);
        }

        return response()->json([
            'status' => true,
            'data'   => $banner,
        ], 200);
    }

    public function update(Request $request, $id): JsonResponse
    {
        try {
            $validator = Validator::make($request->all(), [
                'image'             => 'sometimes|required|string',
                'type'              => 'sometimes|required|in:course,toolkit',
                'related_id'        => 'sometimes|required|integer',
                'short_description' => 'sometimes|nullable|string',
                'status'            => 'sometimes|required|boolean',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'status'  => false,
                    'message' => $validator->errors()->first(),
                ], 400);
            }

            $banner = Banner::find($id);

            if (!$banner) {
                return response()->json([
                    'status'  => false,
                    'message' => 'Banner not found',
                ], 404);
            }

            $banner->update($request->all());

            return response()->json([
                'status'  => true,
                'message' => 'Banner updated successfully',
                'data'    => $banner,
            ], 200);
        } catch (QueryException $e) {
            return response()->json([
                'status'  => false,
                'message' => 'Database query error: ' . $e->getMessage(),
            ], 500);
        } catch (\Exception $e) {
            return response()->json([
                'status'  => false,
                'message' => 'An error occurred: ' . $e->getMessage(),
            ], 500);
        }
    }

    public function destroy($id): JsonResponse
    {
        try {
            $banner = Banner::find($id);

            if (!$banner) {
                return response()->json([
                    'status'  => false,
                    'message' => 'Banner not found',
                ], 404);
            }

            $banner->delete();

            return response()->json([
                'status'  => true,
                'message' => 'Banner deleted successfully',
            ], 200);
        } catch (QueryException $e) {
            return response()->json([
                'status'  => false,
                'message' => 'Database query error: ' . $e->getMessage(),
            ], 500);
        } catch (\Exception $e) {
            return response()->json([
                'status'  => false,
                'message' => 'An error occurred: ' . $e->getMessage(),
            ], 500);
        }
    }
}
