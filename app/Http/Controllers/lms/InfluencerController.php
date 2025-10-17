<?php

namespace App\Http\Controllers\lms;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Influencer;
use App\Models\ReferralCode;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Log;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class InfluencerController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $influencers = Influencer::with('referralCode')->get();
        $influencers_main = $influencers->map(function ($influencer) {
            return [
                'id' => $influencer->id,
                'name' => $influencer->name ?? null,
                'email' => $influencer->email ?? null,
                'phone' => $influencer->phone ?? null,
                'image' => $influencer->image ?? null,
                'kyc_docs' => $influencer->kyc_docs ?? null,
                'commission_per_user' => $influencer->commission_per_user ?? null,
                'code' => $influencer->referralCode->code ?? null,
                'link' => $influencer->referralCode->deeplink_url ?? null,
            ];
        });

        return view('lms.sections.influencer.influencers')->with('influencers_main', $influencers_main);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('lms.sections.influencer.add');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        try {
            Log::info('Influencer store request received', $request->all());

            $validator = Validator::make($request->all(), [
                'name'                => 'required|string|min:3',
                'email'               => 'nullable|email|unique:influencers,email',
                'phone'               => 'nullable|string|unique:influencers,phone',
                'image'               => 'nullable|image|mimes:jpg,jpeg,png,gif,webp|max:2048',
                'kyc_docs'            => 'nullable|file|mimes:pdf,jpg,jpeg,png,gif,webp|max:2048',
                'commission_per_user' => 'required|numeric|min:0',
            ]);

            if ($validator->fails()) {
                Log::warning('Influencer validation failed', [
                    'errors' => $validator->errors()->toArray()
                ]);

                if ($request->ajax()) {
                    return response()->json([
                        'success' => false,
                        'errors'  => $validator->errors(),
                    ], 422);
                }

                return redirect()->back()->withErrors($validator)->withInput();
            }

            DB::beginTransaction();

            $imagePath = null;
            if ($request->hasFile('image')) {
                $imagePath = $request->file('image')->store('uploads/images/Influencers', 'public');
            }

            $kyc = null;
            if ($request->hasFile('kyc_docs')) {
                $kyc = $request->file('kyc_docs')->store('uploads/images/Influencers/kyc', 'public');
            }

            // generate unique referral code
            do {
                $code = 'RFC' . strtoupper(Str::random(6));
            } while (ReferralCode::where('code', $code)->exists());

            $referralCode = ReferralCode::create([
                'code'         => $code,
                'generated_by' => auth()->id(),
                'type'         => 'influencer',
                'deeplink_url' => env('APP_URL') . '?referral_code=' . $code,
                'is_active'    => true,
            ]);

            $influencer = Influencer::create([
                'name'                => $request->input('name'),
                'email'               => $request->input('email'),
                'phone'               => $request->input('phone'),
                'image'               => $imagePath,
                'kyc_docs'            => $kyc,
                'commission_per_user' => $request->input('commission_per_user'),
                'referral_code_id'    => $referralCode->id,
            ]);

            DB::commit();

            Log::info('Influencer created successfully', [
                'id'   => $influencer->id,
                'name' => $influencer->name,
            ]);

            if ($request->ajax()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Influencer created successfully!',
                ]);
            }

            return redirect()->route('lms.influencers')
                ->with('success', 'Influencer created successfully.');

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error while creating Influencer', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);

            if ($request->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Something went wrong while creating the influencer.',
                    'error' => $e->getMessage(),
                ], 500);
            }

            return back()->withErrors(['error' => 'Something went wrong while creating the influencer.']);
        }
    }


    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        Log::info('Influencer update request received', [
            'id'   => $id,
            'data' => $request->except(['_token', 'image', 'kyc_docs'])
        ]);

        // manual validator so we can return JSON if ajax
        $validator = Validator::make($request->all(), [
            'name'                => 'nullable|string|min:3',
            'email'               => 'nullable|email|unique:influencers,email,' . $id,
            'phone'               => 'nullable|string|unique:influencers,phone,' . $id,
            'image'               => 'nullable|image|mimes:jpg,jpeg,png,gif,webp|max:2048',
            'kyc_docs'            => 'nullable|file|mimes:pdf,jpg,jpeg,png,gif,webp|max:2048',
            'commission_per_user' => 'nullable|numeric|min:0',
        ]);

        if ($validator->fails()) {
            Log::warning('Influencer update validation failed', [
                'id'     => $id,
                'errors' => $validator->errors()->toArray()
            ]);

            if ($request->ajax()) {
                return response()->json([
                    'success' => false,
                    'errors'  => $validator->errors(),
                ], 422);
            }

            return redirect()->back()->withErrors($validator)->withInput();
        }

        $influencer = Influencer::find($id);
        if (!$influencer) {
            Log::warning('Influencer not found', ['id' => $id]);

            if ($request->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Influencer not found'
                ], 404);
            }

            return redirect()->back()->with('error', 'Influencer not found');
        }

        // Handle image
        $path = $influencer->image;
        if ($request->hasFile('image')) {
            $existing_image = storage_path('app/public/' . $influencer->image);
            if ($influencer->image && File::exists($existing_image)) {
                File::delete($existing_image);
                Log::info('Old influencer image deleted', ['path' => $existing_image]);
            }
            $path = $request->file('image')->store('uploads/images/Influencers', 'public');
            Log::info('New influencer image uploaded', ['path' => $path]);
        }

        // Handle KYC docs
        $kycPath = $influencer->kyc_docs;
        if ($request->hasFile('kyc_docs')) {
            $existing_kyc = storage_path('app/public/' . $influencer->kyc_docs);
            if ($influencer->kyc_docs && File::exists($existing_kyc)) {
                File::delete($existing_kyc);
                Log::info('Old influencer KYC deleted', ['path' => $existing_kyc]);
            }
            $kycPath = $request->file('kyc_docs')->store('uploads/images/Influencers/kyc', 'public');
            Log::info('New influencer KYC uploaded', ['path' => $kycPath]);
        }

        try {
            $influencer->update([
                'name'                => $request->input('name', $influencer->name),
                'email'               => $request->input('email', $influencer->email),
                'phone'               => $request->input('phone', $influencer->phone),
                'image'               => $path,
                'kyc_docs'            => $kycPath,
                'commission_per_user' => $request->input('commission_per_user', $influencer->commission_per_user),
            ]);

            Log::info('Influencer updated successfully', ['id' => $influencer->id]);

            if ($request->ajax()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Influencer updated successfully!',
                ]);
            }

            return redirect()
                ->route('lms.influencers')
                ->with('message', 'Influencer updated successfully');

        } catch (\Illuminate\Database\QueryException $e) {
            Log::error('Database error while updating Influencer', [
                'id'    => $id,
                'error' => $e->getMessage()
            ]);

            if ($request->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Database error occurred while updating influencer.',
                ], 500);
            }

            return redirect()->back()->with('error', 'Database error occurred while updating influencer.');
        } catch (\Exception $e) {
            Log::error('Unexpected error while updating Influencer', [
                'id'    => $id,
                'error' => $e->getMessage()
            ]);

            if ($request->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Failed to update influencer: ' . $e->getMessage(),
                ], 500);
            }

            return redirect()->back()->with('error', 'Failed to update influencer: ' . $e->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
         try {
            $influencer = Influencer::find($id);
    
            if (!$influencer) {
                return redirect()->back()->with('error', 'Influencer not found');
            }
    
            $influencer->delete();
    
            return response()->json(['success' => true, 'message' => 'Influencer deleted successfully']);
        } catch (QueryException $e) {
            return redirect()->back()->with('error', 'Database error: ' . $e->getMessage());
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'An error occurred: ' . $e->getMessage());
        }
    }
    public function ajaxList(Request $request)
    {
        $influencers = Influencer::with('referralCode')
            ->select(['id', 'name', 'email', 'phone', 'image', 'commission_per_user','referral_code_id'])->latest();

        return DataTables::of($influencers)
            ->addColumn('image', function ($influencer) {
                return $influencer->image
                    ? '<img src="' . asset('storage/' . $influencer->image) . '" class="me-2 rounded-circle" width="40">'
                    : '<span class="small text-danger">No Image</span>';
            })
            ->addColumn('email', function ($influencer) {
                return $influencer->email ?: '<span class="small text-danger">No Email</span>';
            })
            ->addColumn('phone', function ($influencer) {
                return $influencer->phone ?: '<span class="small text-danger">No Phone</span>';
            })
            ->addColumn('code', function ($influencer) {
                return $influencer->referralCode->code ?? '<span class="small text-danger">No Code</span>';
            })
            ->addColumn('commission', function ($influencer) {
                return $influencer->commission_per_user ?: '<span class="small text-danger">No Commission</span>';
            })
            ->addColumn('action', function ($influencer) {
                $link = $influencer->referralCode->link ?? '#';
                $actions = '<a href="javascript:void(0);" class="action-icon copy-link" data-link="' . $link . '" title="Copy Link">
                                <i class="mdi mdi-content-copy"></i>
                            </a>
                            <span class="copy-success text-success ms-1 d-none">Copied!</span>';

                if (auth()->user()->hasPermission('influencers.update')) {
                    $actions .= '<a href="javascript:void(0);" class="action-icon" data-bs-toggle="modal" data-bs-target="#bs-editInfluencer-modal' . $influencer->id . '">
                                    <i class="mdi mdi-square-edit-outline"></i>
                                </a>';
                }

                if (auth()->user()->hasPermission('influencers.delete')) {
                    $actions .= '<a href="javascript:void(0);" class="action-icon" data-bs-toggle="modal" data-bs-target="#delete-alert-modal' . $influencer->id . '">
                                    <i class="mdi mdi-delete"></i>
                                </a>';
                }

                // Include modals in the response
                $editModal = view('lms.sections.influencer.inc.edit-influence-modal', compact('influencer'))->render();
                $deleteModal = view('lms.sections.influencer.inc.delete-influence-modal', compact('influencer'))->render();

                return $actions . $editModal . $deleteModal;
            })
            ->rawColumns(['image', 'email', 'phone', 'code', 'commission', 'action'])
            ->make(true);
    }
}
