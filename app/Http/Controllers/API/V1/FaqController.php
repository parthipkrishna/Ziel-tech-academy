<?php

namespace App\Http\Controllers\API\V1;

use App\Http\Controllers\Controller;
use App\Models\Faq;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\Validator;

class FaqController extends Controller
{
    public function __construct()
    {
        $this->middleware(function ($request, $next) {
            if ($request->route()->getActionMethod() !== 'index') {
                $this->userId = auth()->user()->user_id;
            }
            return $next($request);
        });
    }
    
   public function index(Request $request): JsonResponse
    {
        $faqs = Faq::get(); // Correct model usage
    
        if ($faqs->isEmpty()) {
            return response()->json([
                'status' => true,
                'message' => 'No FAQs found',
            ], 200);
        }
    
        return response()->json([
            'status' => true,
            'data' => $faqs,
        ], 200);
    }

    public function store(Request $request): JsonResponse
    {
        try {
            $validator = Validator::make($request->all(), [
                'question' => 'required|string',
                'answer' => 'required|string',
                'is_enable' => 'required|boolean',
            ]);
    
            if ($validator->fails()) {
                return response()->json([
                    'status' => false,
                    'message' => $validator->errors()->first(),
                ], 400);
            }

            $faq = Faq::create([
                'question' => $request->input('question'),
                'answer' => $request->input('answer'),
                'is_enable' => $request->input('is_enable')
            ]);
    
            return response()->json([
                'status' => true,
                'message' => 'FAQ added successfully',
                'data' => $faq,
            ], 200);
        } catch (QueryException $e) {
            return response()->json([
                'status' => false,
                'message' => 'Database query error: ' . $e->getMessage(),
            ], 500);
        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'message' => 'An error occurred: ' . $e->getMessage(),
            ], 500);
        }
    }

    public function show($id): JsonResponse
    {
        $faq = Faq::find($id);

        if (!$faq) {
            return response()->json([
                'status' => false,
                'message' => 'FAQ not found',
            ], 404);
        }

        return response()->json([
            'status' => true,
            'data' => $faq,
        ], 200);
    }

    public function update(Request $request, $id): JsonResponse
    {
        try {
            $validator = Validator::make($request->all(), [
                'question' => 'sometimes|required|string',
                'answer' => 'sometimes|required|string',
                'is_enable' => 'sometimes|required|boolean',
            ]);
    
            if ($validator->fails()) {
                return response()->json([
                    'status' => false,
                    'message' => $validator->errors()->first(),
                ], 400);
            }

            $faq = Faq::find($id);

            if (!$faq) {
                return response()->json([
                    'status' => false,
                    'message' => 'FAQ not found',
                ], 404);
            }

            $faq->update($request->all());
    
            return response()->json([
                'status' => true,
                'message' => 'FAQ updated successfully',
                'data' => $faq,
            ]);
        } catch (QueryException $e) {
            return response()->json([
                'status' => false,
                'message' => 'Database query error: ' . $e->getMessage(),
            ], 500);
        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'message' => 'An error occurred: ' . $e->getMessage(),
            ], 500);
        }
    }

    public function destroy($id): JsonResponse
    {
        try {
            $faq = Faq::find($id);

            if (!$faq) {
                return response()->json([
                    'status' => false,
                    'message' => 'FAQ not found',
                ], 404);
            }

            $faq->delete();

            return response()->json([
                'status' => true,
                'message' => 'FAQ deleted successfully',
            ]);
        } catch (QueryException $e) {
            return response()->json([
                'status' => false,
                'message' => 'Database query error: ' . $e->getMessage(),
            ], 500);
        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'message' => 'An error occurred: ' . $e->getMessage(),
            ], 500);
        }
    }
}
