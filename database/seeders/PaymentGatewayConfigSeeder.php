<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Carbon;

class PaymentGatewayConfigSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('payment_gateway_configs')->insert([
            [
                'gateway_name'     => 'razorpay',
                'display_name'     => 'Razorpay',
                'status'           => 'active',
                'api_key'          => env('RAZORPAY_KEY', 'rzp_test_ofgNlVLOJ58pLt'),
                'api_secret'       => env('RAZORPAY_SECRET', 'AHfJEpm0uCD4bVVrksPEfSoQ'),
                'webhook_secret'   => env('RAZORPAY_WEBHOOK_SECRET', 'whsec_test_123456'),
                'meta'             => json_encode(['note' => 'Default Razorpay config']),
                'created_at'       => Carbon::now(),
                'updated_at'       => Carbon::now(),
            ],
            [
                'gateway_name'     => 'stripe',
                'display_name'     => 'Stripe',
                'status'           => 'inactive',
                'api_key'          => 'sk_test_ABC1234567890',
                'api_secret'       => 'secret_test_stripe',
                'webhook_secret'   => null,
                'meta'             => json_encode(['mode' => 'test']),
                'created_at'       => Carbon::now(),
                'updated_at'       => Carbon::now(),
            ],
        ]);
    }
}
