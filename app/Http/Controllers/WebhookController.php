<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use App\Models\User;
use App\Models\PaymentMethod;
use App\Models\Subscription;
use App\Models\TransactionHistory;

class WebhookController extends Controller
{
    public function webhookResponse(Request $request)
    {
        switch ($request->type) {
            case 'customer.created':
                // Customer created in stripe
                User::where('email', $request->data['object']['email'])->update(['stripe_id' => $request->data['object']['id']]);
                break;

            case 'payment_method.attached':
                // Payment method attached to a customer
                // get user id from customer stripe id
                Log::info(['$request[object][customer]' => $request->data['object']['customer']]);
                $user = User::where('stripe_id', $request->data['object']['customer'])->first();
                PaymentMethod::create([
                    'user_id' => $user->id,
                    'payment_methods' => $request->data['object']['id']
                ]);
                break;

            case 'customer.subscription.created':
                // Subscription created for a customer
                $user = User::where('stripe_id', $request->data['object']['customer'])->first();
                Subscription::create([
                    'user_id' => $user->id,
                    'subscription_id' => $request->data['object']['id'],
                    'status' => $request->data['object']['status']
                ]);
                break;

            case 'customer.subscription.deleted':
                // Subscription cancelled for a customer
                $user = User::where('stripe_id', $request->data['object']['customer'])->first();
                Subscription::where('subscription_id', $request->data['object']['id'])->update([
                    'status' => $request->data['object']['status']
                ]);
                break;

            case 'charge.succeeded':
                // Charge succeeded for payment intent
                Log::info(['charge succeed event' => $request->data['object']]);
                $user = User::where('stripe_id', $request->data['object']['customer'])->first();
                TransactionHistory::create([
                    'user_id' => $user->id,
                    'pi_id' => $request->data['object']['payment_intent'],
                    'receipt_url' => $request->data['object']['receipt_url'],
                    'status' => ($request->data['object']['refunded']) ? 'refunded' : 'succeeded',
                    'amount' => $request->data['object']['amount']/100,
                    'message' => null,
                ]);
                break;

            case 'charge.failed':
                // Charge failed for payment intent
                $user = User::where('stripe_id', $request->data['object']['customer'])->first();
                TransactionHistory::create([
                    'user_id' => $user->id,
                    'pi_id' => $request->data['object']['payment_intent'],
                    'receipt_url' => $request->data['object']['receipt_url'],
                    'status' => 'failed',
                    'amount' => $request->data['object']['amount']/100,
                    'message' => $request->data['object']['failure_message']
                ]);
                break;

            case 'charge.refunded':
                // Charge refunded with payment intent
                $user = User::where('stripe_id', $request->data['object']['customer'])->first();
                TransactionHistory::where('pi_id', $request->data['object']['payment_intent'])->update([
                    'receipt_url' => $request->data['object']['receipt_url'],
                    'status' => 'refunded',
                ]);
                break;

            default:
                # code...
                break;
        }
    }
}
