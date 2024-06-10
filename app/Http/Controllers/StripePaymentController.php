<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use stripe;

class StripePaymentController extends Controller
{
    public function stripePost(Request $request)
    {
        $request->validate([
            'amount' => 'required|numeric|min:1'
        ]);
        try {
            $stripe = new \Stripe\StripeClient(
                env('STRIPE_SECRET')
            );
            // استخدام توكن اختبار جاهز
            $token = 'tok_visa'; // يمكنك تغيير هذا إلى توكن اختبار آخر إذا لزم الأمر
            $charge = $stripe->charges->create([
                'amount' => $request->amount * 100, // تحويل المبلغ إلى سنتات
                'currency' => 'egp',
                'source' => $token,
                'description' => 'My First Test Charge (created for API docs)',
            ]);
            return response()->json(['message' => 'Payment Successful', 'status' => 'Success', 'data' => $charge], 200);
        } catch (Exception $e) {
            return response()->json(['message' => $e->getMessage(), 'status' => 'Error'], 500);
        }
    }
}




