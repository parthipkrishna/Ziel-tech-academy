<?php

namespace App\Http\Controllers\lms;

use App\Http\Controllers\Controller;
use App\Models\PaymentGatewayConfig;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\Facades\DataTables; 
use Illuminate\Support\Facades\Log;
use Exception;

class PaymentConfigController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {   
        $paymentConfigs = PaymentGatewayConfig::latest()->get();
         return view('lms.sections.payment_config.index',compact('paymentConfigs'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('lms.sections.payment_config.add');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        Log::info('Payment Gateway store request received', [
            'data' => $request->except(['_token'])
        ]);

        $validated = $request->validate([
            'gateway_name'    => 'required|unique:payment_gateway_configs,gateway_name',
            'display_name'    => 'nullable|string',
            'api_key'         => 'nullable|string',
            'api_secret'      => 'nullable|string',
            'webhook_secret'  => 'nullable|string',
            'meta'            => 'nullable|string',
            'status'          => 'nullable|in:active,inactive',
        ]);

        $status = $request->input('status') === 'active' ? 'active' : 'inactive';

        try {
            DB::transaction(function () use ($validated, $status) {
                // Deactivate other gateways if this one is active
                if ($status === 'active') {
                    PaymentGatewayConfig::where('status', 'active')->update(['status' => 'inactive']);
                    Log::info('Other active gateways deactivated.');
                }

                $gateway = PaymentGatewayConfig::create([
                    'gateway_name'    => $validated['gateway_name'],
                    'display_name'    => $validated['display_name'] ?? null,
                    'api_key'         => $validated['api_key'] ? bcrypt($validated['api_key']) : null,
                    'api_secret'      => $validated['api_secret'] ? bcrypt($validated['api_secret']) : null,
                    'webhook_secret'  => $validated['webhook_secret'] ? bcrypt($validated['webhook_secret']) : null,
                    'meta'            => !empty($validated['meta']) ? trim($validated['meta']) : '',
                    'status'          => $status,
                ]);

                Log::info('Payment Gateway created successfully', ['id' => $gateway->id]);
            });

            return redirect()
                ->route('lms.payment-config.index')
                ->with('success', 'Payment Gateway created successfully.');

        } catch (\Illuminate\Database\QueryException $e) {
            Log::error('Database error while creating Payment Gateway', [
                'error' => $e->getMessage()
            ]);

            if ($e->getCode() === '23000') { // Unique constraint violation
                return back()->withInput()->with(
                    'error',
                    'This gateway name already exists. Please choose a different one.'
                );
            }

            return back()->withInput()->with(
                'error',
                'Something went wrong while saving the payment gateway. Please contact support.'
            );

        } catch (\Exception $e) {
            Log::error('Unexpected error while creating Payment Gateway', [
                'error' => $e->getMessage()
            ]);

            return back()->withInput()->with(
                'error',
                'An unexpected error occurred. Please try again later.'
            );
        }
    }


    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    public function toggleStatus(Request $request, $id)
    {
        $gateway = PaymentGatewayConfig::findOrFail($id);
        $status = $request->status;

        DB::transaction(function () use ($gateway, $status) {
            if ($status === 'active') {
                PaymentGatewayConfig::where('id', '!=', $gateway->id)->update(['status' => 'inactive']);
                $gateway->update(['status' => 'active']);
            } else {
                if (PaymentGatewayConfig::where('status', 'active')->count() === 1) {
                    throw new \Exception('At least one payment gateway must be active.');
                }
                $gateway->update(['status' => 'inactive']);
            }
        });

        return response()->json(['message' => 'Gateway status updated successfully.']);
    }

    public function ajaxList(Request $request)
    {
        $configs = PaymentGatewayConfig::select(['id', 'gateway_name', 'display_name', 'status', 'updated_at'])->latest();

        return DataTables::of($configs)
            ->editColumn('updated_at', function ($config) {
                return $config->updated_at->format('d-m-Y'); 
            })
            ->addColumn('status', function ($config) {
                $checked = $config->status === 'active' ? 'checked' : '';
                $disabled = ($config->status === 'active' && PaymentGatewayConfig::where('status', 'active')->count() === 1) ? 'disabled' : '';

                return '
                    <div>
                        <input type="checkbox" class="status-toggle" data-id="' . $config->id . '" id="switch' . $config->id . '" ' . $checked . ' data-switch="success">
                        <label for="switch' . $config->id . '" data-on-label="Active" data-off-label="Inactive" class="mb-0 d-block"></label>
                    </div>';
            })
            ->addColumn('action', function ($config) {
                $actions = '';

                if (auth()->user()->hasPermission('payment-config.update')) {
                    $actions .= '<a href="javascript:void(0);" class="action-icon editFaqBtn" data-bs-toggle="modal" data-bs-target="#edit-config-modal' . $config->id . '"><i class="mdi mdi-square-edit-outline"></i></a>';
                }

                if (auth()->user()->hasPermission('payment-config.delete')) {
                    $actions .= '<a href="javascript:void(0);" class="action-icon deleteFaqBtn" data-bs-toggle="modal" data-bs-target="#delete-config-modal' . $config->id . '"><i class="mdi mdi-delete"></i></a>';
                }

                return $actions;
            })
            ->rawColumns(['status', 'action'])
            ->make(true);
    }


    /**
     * Show the form for editing the specified resource.
     */
    public function update(Request $request, $id) 
    {
        Log::info('Payment Gateway update request received', [
            'id'   => $id,
            'data' => $request->except(['_token'])
        ]);

        $validated = $request->validate([
            'display_name'    => 'nullable|string|max:255',
            'api_key'         => 'nullable|string',
            'api_secret'      => 'nullable|string',
            'webhook_secret'  => 'nullable|string',
            'meta'            => 'nullable|json',
            'status'          => 'nullable|in:active,inactive',
        ]);

        try {
            DB::transaction(function () use ($validated, $id, $request) {
                $config = PaymentGatewayConfig::findOrFail($id);

                $config->display_name = $validated['display_name'] ?? $config->display_name;

                if ($request->filled('api_key')) {
                    $config->api_key = bcrypt($validated['api_key']);
                }

                if ($request->filled('api_secret')) {
                    $config->api_secret = bcrypt($validated['api_secret']);
                }

                if ($request->filled('webhook_secret')) {
                    $config->webhook_secret = bcrypt($validated['webhook_secret']);
                }

                if ($request->filled('meta')) {
                    $config->meta = $validated['meta'];
                }

                // If marked active, set all others inactive
                if ($request->status === 'active') {
                    PaymentGatewayConfig::where('id', '!=', $config->id)->update(['status' => 'inactive']);
                    $config->status = 'active';
                    Log::info('Other active gateways deactivated during update', ['id' => $config->id]);
                } else {
                    $config->status = 'inactive';
                }

                $config->save();

                Log::info('Payment Gateway updated successfully', ['id' => $config->id]);
            });

            return redirect()
                ->route('lms.payment-config.index')
                ->with('success', 'Payment config updated successfully.');

        } catch (\Illuminate\Database\QueryException $e) {
            Log::error('Database error while updating Payment Gateway', [
                'id'    => $id,
                'error' => $e->getMessage()
            ]);

            return back()->withInput()->with(
                'error',
                'Something went wrong while updating the payment gateway. Please contact support.'
            );

        } catch (\Exception $e) {
            Log::error('Unexpected error while updating Payment Gateway', [
                'id'    => $id,
                'error' => $e->getMessage()
            ]);

            return back()->withInput()->with(
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
        $config = PaymentGatewayConfig::findOrFail($id);
        $config->delete();

        return response()->json(['message' => 'Payment config deleted successfully.']);
    }

}
