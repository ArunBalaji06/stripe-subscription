<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Library\Stripe;
use App\Models\User;

class CustomerController extends Controller
{
    use Stripe;

    public $user;

    public function __construct(User $user)
    {
        $this->user = $user;
    }

    /**
     * Landing page
     */
    public function index()
    {
        $users = $this->user->get();
        return view('welcome', compact('users'));
    }

    /**
     * @param Request $request
     * Store customer in stripe
     */
    public function storeCustomer(Request $request)
    {
        $data['name'] = $request->name;
        $data['email'] = $request->email;
        $data['phone'] = $request->phone;
        $this->createCustomer((object)$data);
        $this->user->create([
            'name' => $request->name,
            'email' => $request->email,
            'phone' => $request->phone,
        ]);
        return redirect('/');
    }

    /**
     * @param Request $request
     * Store price in stripe
     */
    public function storePrice(Request $request)
    {
        $data['name'] = $request->name;
        $data['price'] = $request->price;
        $data['interval'] = $request->interval;
        $this->createPrice((object)$data);
        return redirect('/');
    }

    /**
     * List all prices in connected stripe account
     */
    public function listPrices()
    {
        return $this->listAllPrices();
    }

    /**
     * List all customers in connected stripe account
     */
    public function listCustomers()
    {
        return $this->listAllCustomers();
    }

    /**
     * Add payment method in stripe
     * Attach to a customer
     * Make default payment
     */
    public function attachPm()
    {
        // Create payment method
        $paymentMethod = $this->addPaymentMethod();
        $data['customer'] = 'cus_PLLwXgeYy0qW7l'; // Customer id
        $data['pm_id'] = $paymentMethod->id; // Payment method id
        // Attach payment method to customer
        $this->attachPaymentMethod($data);

        return back();
    }

    /**
     * Do subcription to a customer
     */
    public function subscription()
    {
        $data['price'] = 'price_1OWC4QHkIHsq7znWo1NSjJLj';  // Price id
        $data['customer'] = 'cus_PLLwXgeYy0qW7l';   // Customer id
        // Add subscription to customer
        $subscription = $this->doSubscription($data);
        return back();

    }

    /**
     * Cancel subscription with subscription id
     */
    public function cancelSubscription()
    {
        $data['subscription_id'] = 'sub_1OWbG8HkIHsq7znWpJzIaJE8';  // Subscription id
        $this->subscriptionCancellation($data);
        return back();
    }

    /**
     * 3D secure payment flow with payment intent
     * Confirm payment intent if required
     */
    public function threeDSecurePayment()
    {
        $data['amount'] = '1';  // Price to e charged
        $data['currency'] = 'USD';  // Currency for payment
        $data['user_id'] = 1;   // Our platform local customer id
        $data['description'] = '1 test payment';    //Payment intent description
        $data['payment_method'] = 'pm_1OWfGmHkIHsq7znWmz2286g5';    // Payment methods
        $data['customer'] = 'cus_PLLwXgeYy0qW7l';
        $paymentIntent = $this->ThreeDPayment($data);
        if ($paymentIntent->status == 'requires_action') {
            $paymentIntentArray['id'] = $paymentIntent->id;
            $paymentIntentArray['payment_method'] = $paymentIntent->payment_method;
            $confirmIntent = $this->confirmPaymentIntent($paymentIntentArray);
            return redirect()->away($confirmIntent->next_action['redirect_to_url']['url']);
        }
        return back();
    }

    /**
     * Refund the price
     * Refund done by payment intent id
     * Refund amount should not be greater than the paid amount in that intent
     */
    public function refund()
    {
        $data['pi_id']  = 'pi_3OWfgZHkIHsq7znW0U1roGRV';    // Payment intent id
        $data['amount'] = 1;
        $this->refundPayment($data);
        return back();
    }
}
