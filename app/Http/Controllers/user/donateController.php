<?php

namespace App\Http\Controllers\user;

use App\Http\Controllers\Controller;
use App\Models\Fundraisers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Stripe\StripeClient;
use App\Models\Transaction;
use Exception;

class donateController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:sanctum');
    }
    private function isUserAllowedToDonate($user)
    {
        if ($user->is_charity == 1) {
            return res_data('error', 'You are not allowed to donate');
        }
        return null;
    }

    private function checkRemainingAmount($fundraiser, $amount)
    {
        $remainingAmount = $fundraiser->goal - $fundraiser->raised;
        if ($amount > $remainingAmount) {
            return res_data('error', 'The amount you are trying to donate exceeds the required amount. The required amount is ' . $remainingAmount . ' EGP.', 400);
        }
        return null;
    }

    private function createStripeCharge($amount, $fundraiserId)
    {
        $stripe = new StripeClient(env('STRIPE_SECRET'));
        $token = 'tok_visa';
        return $stripe->charges->create([
            'amount' => $amount * 100, // تحويل المبلغ إلى سنتات
            'currency' => 'egp',
            'source' => $token,
            'description' => 'Donation to Fundraiser ID: ' . $fundraiserId,
        ]);
    }


    public function donate(Request $request)
    {
        $user = Auth::user();

        // التحقق من صلاحية المستخدم
        $notAllowedResponse = $this->isUserAllowedToDonate($user);
        if ($notAllowedResponse) {
            return $notAllowedResponse;
        }

        // التحقق من البيانات المدخلة
        $request->validate([
            'amount' => 'required|numeric|min:1',
            'fundraiser_id' => 'required|exists:fundraisers,id'
        ]);

        // الحصول على حملة جمع التبرعات
        $fundraiser = Fundraisers::find($request->fundraiser_id);

        // التحقق من المبلغ المتبقي
        $amountCheckResponse = $this->checkRemainingAmount($fundraiser, $request->amount);
        if ($amountCheckResponse) {
            return $amountCheckResponse;
        }

        try {
            // إنشاء عملية الدفع عبر Stripe
            $charge = $this->createStripeCharge($request->amount, $request->fundraiser_id);

            // تخزين بيانات الدفع في قاعدة البيانات
            $transaction = Transaction::create([
                'user_id' => $user->id,
                'fundraiser_id' => $request->fundraiser_id,
                'amount' => $charge->amount / 100, // تحويل المبلغ إلى وحدات العملة
                'status' => $charge->status,
                'transaction_id' => $charge->id,
                'description' => $charge->description,
            ]);

            if ($charge->status == 'succeeded') {
                // تحديث المبلغ المجموع في الحملة
                $fundraiser->raised += $transaction->amount;
                $fundraiser->save();

                return res_data([
                    'transaction' => $transaction,
                    'fundraiser' => $fundraiser,
                    'charge' => $charge,
                ], 'success', 200);
            } else {
                return res_data('error', 'Payment Failed');
            }
        } catch (Exception $e) {
            return response()->json(['message' => $e->getMessage(), 'status' => 'Error'], 500);
        }
    }


    public function transactions()
    {
        $user = Auth::user();
        $transactions = $user->transactions;
        return res_data($transactions, 'success', 200);
    }
}
