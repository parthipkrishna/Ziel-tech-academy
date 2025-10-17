<?php

namespace App\Http\Controllers\lms;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Faq;
use Yajra\DataTables\Facades\DataTables;
use Str;
use Illuminate\Support\Facades\Log;
use Exception;

class FaqController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $faqs = Faq::get();
        return view('lms.sections.faq.faq')->with(compact('faqs'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('lms.sections.faq.add');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        Log::info('FAQ store request received', $request->all());

        $request->validate([
            'question'   => 'required|string',
            'answer'     => 'required|string',
            'is_enable'  => 'required|boolean',
        ]);

        try {
            $faq = Faq::create([
                'question'   => $request->input('question'),
                'answer'     => $request->input('answer'),
                'is_enable'  => $request->input('is_enable'),
            ]);

            Log::info('FAQ created successfully', ['faq_id' => $faq->id]);

            return redirect()
                ->route('lms.faqs')
                ->with('message', 'FAQ added successfully.');

        } catch (\Illuminate\Database\QueryException $e) {
            Log::error('Database error while creating FAQ', [
                'error' => $e->getMessage()
            ]);

            if ($e->getCode() === '23000') { 
                return back()->withInput()->with(
                    'error', 
                    'This FAQ already exists. Please use a different question.'
                );
            }

            return back()->withInput()->with(
                'error', 
                'Something went wrong while saving your data. Please contact support.'
            );

        } catch (\Exception $e) {
            Log::error('Unexpected error while creating FAQ', [
                'error' => $e->getMessage()
            ]);

            return back()->withInput()->with(
                'error', 
                'An unexpected error occurred. Please try again later.'
            );
        }
    }
    
    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        Log::info('Attempting to update FAQ.', [
            'faq_id' => $id,
            'request_data' => $request->except(['_token', '_method'])
        ]);

        try {
            $request->validate([
                'question'   => 'sometimes|required|string',
                'answer'     => 'sometimes|required|string',
                'is_enable'  => 'sometimes|required|boolean',
            ]);

            $faq = Faq::find($id);

            if (!$faq) {
                Log::warning('FAQ not found for update.', ['faq_id' => $id]);
                return redirect()->back()->with('error', 'FAQ not found');
            }

            Log::info('FAQ found for update.', ['faq_id' => $faq->id]);

            $faq->update([
                'question'  => $request->input('question', $faq->question),
                'answer'    => $request->input('answer', $faq->answer),
                'is_enable' => $request->has('is_enable') ? (bool) $request->is_enable : $faq->is_enable,
            ]);

            Log::info('FAQ updated successfully.', [
                'faq_id' => $faq->id,
                'question' => $faq->question
            ]);

            return redirect()->route('lms.index.faq')
                ->with('message', 'FAQ updated successfully');

        } catch (\Illuminate\Database\QueryException $e) {
            Log::error('Database error while updating FAQ.', [
                'error' => $e->getMessage(),
                'faq_id' => $id,
                'request' => $request->all()
            ]);

            if ($e->getCode() === '23000') {
                return redirect()->back()->withInput()->with(
                    'error',
                    'This question already exists. Please use a different one.'
                );
            }

            return redirect()->back()->withInput()->with(
                'error',
                'Something went wrong while updating the FAQ. Please try again later.'
            );

        } catch (\Exception $e) {
            Log::error('Unexpected error while updating FAQ.', [
                'error' => $e->getMessage(),
                'faq_id' => $id,
                'request' => $request->all()
            ]);

            // Generic error for users
            return redirect()->back()->withInput()->with(
                'error',
                'An unexpected error occurred. Please try again later.'
            );
        }
    }
    

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        try {
            $faq = Faq::find($id);
    
            if (!$faq) {
                return response()->json(['error' => 'FAQ not found'], 404);
            }
    
            $faq->delete();
    
            return response()->json(['message' => 'FAQ deleted successfully']);
        } catch (QueryException $e) {
            return redirect()->back()->with('error', 'Database error: ' . $e->getMessage());
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'An error occurred: ' . $e->getMessage());
        }
    }

    public function ajaxList(Request $request)
    {
        $faqs = Faq::select(['id', 'question', 'answer', 'is_enable'])->latest();

        return DataTables::of($faqs)
            ->editColumn('question', function ($faq) {
                return Str::limit($faq->question, 100);
            })
            ->editColumn('answer', function ($faq) {
                return Str::limit($faq->answer, 100);
            })
            ->addColumn('is_enable', function ($faq) {
                return $faq->is_enable
                    ? '<button class="btn btn-soft-success rounded-pill">Active</button>'
                    : '<button class="btn btn-soft-danger rounded-pill">Inactive</button>';
            })
            ->addColumn('action', function ($faq) {
                $actions = '';

                if (auth()->user()->hasPermission('faqs.update')) {
                    $actions .= '<a href="javascript:void(0);" class="action-icon editFaqBtn" data-bs-toggle="modal" data-bs-target="#edit-faq-modal' . $faq->id . '"><i class="mdi mdi-square-edit-outline"></i></a>';
                }

                if (auth()->user()->hasPermission('faqs.delete')) {
                    $actions .= '<a href="javascript:void(0);" class="action-icon deleteFaqBtn" data-bs-toggle="modal" data-bs-target="#delete-faq-modal' . $faq->id . '"><i class="mdi mdi-delete"></i></a>';
                }

                return $actions;
            })
            ->rawColumns(['is_enable', 'action']) 
            ->make(true);
    }
}
