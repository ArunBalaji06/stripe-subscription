<?php

namespace App\Library;

trait Stripe
{
    private $stripe, $datas;

    /**
     * Library constructor
     */
    public function __construct()
    {
        $this->stripe = new \Stripe\StripeClient(env('STRIPE_SECRET_KEY'));
    }

        /**
     * @param $data
     * @return \Stripe\Customer
     * @throws \Stripe\Exception\ApiErrorException
     */
    public function createCustomer($data)
    {
        $this->stripe = new \Stripe\StripeClient(env('STRIPE_SECRET_KEY'));
        return $this->stripe->customers->create(
            [
                'name' => $data->name,
                'email' => $data->email,
                'phone' => $data->phone
            ]
        );
    }

    /**
     * @param $data
     * Create price with currency, price and interval
     */
    public function createPrice($data)
    {
        $stripe = new \Stripe\StripeClient(env('STRIPE_SECRET_KEY'));
        return $stripe->prices->create([
          'currency' => 'inr',
          'unit_amount' => $data->price*100,
          'recurring' => [
                'interval' => $data->interval
            ],
            'product_data' => [
                'name' => $data->name
            ],
        ]);
    }

    /**
     * List all prices in connected stripe account
     */
    public function listAllPrices()
    {
        $stripe = new \Stripe\StripeClient(env('STRIPE_SECRET_KEY'));
        return $stripe->prices->all();
    }

    /**
     * List all customers in connected stripe account
     */
    public function listAllCustomers()
    {
        $stripe = new \Stripe\StripeClient(env('STRIPE_SECRET_KEY'));
        return $stripe->customers->all();
    }

    /**
     * Add payment method in stripe
     */
    public function addPaymentMethod()
    {
        $this->stripe = new \Stripe\StripeClient(env('STRIPE_SECRET_KEY'));
        //create payment method
        $paymentMethod['type'] = 'card';
        //new card token changes
        /**
         * tok_visa
         * tok_threeDSecure2Required
         */
        $paymentMethod['card']['token'] = 'tok_visa';
        return $this->stripe->paymentMethods->create($paymentMethod);

    }

    public function getCustomerSubscription($data)
    {
        $stripe = new \Stripe\StripeClient(env('STRIPE_SECRET_KEY'));
        return $stripe->subscriptions->all([
            'customer' => $data->customer
        ]);
    }

    /**
     * @param $data
     * Attach payment method to customer
     * Make default payment method
     */
    public function attachPaymentMethod($data)
    {
        $stripe = new \Stripe\StripeClient(env('STRIPE_SECRET_KEY'));

        // Attach payment method to customer
        $stripe->paymentMethods->attach(
            $data['pm_id'],
            ['customer' => $data['customer']]
          );
        // Make default payment method to a customer
        return $stripe->customers->update(
            $data['customer'],
            ['invoice_settings' => ['default_payment_method' => $data['pm_id']]]
        );
    }

    /**
     * @param $data
     * Create subcription to a customer
     */
    public function doSubscription($data)
    {
        $stripe = new \Stripe\StripeClient(env('STRIPE_SECRET_KEY'));
        return $stripe->subscriptions->create([
            'customer' => $data['customer'],
            'items' => [['price' => $data['price']]],
          ]);
    }

    /**
     * @param $data
     * Cancel subscription
     */
    public function subscriptionCancellation($data)
    {
        $stripe = new \Stripe\StripeClient(env('STRIPE_SECRET_KEY'));
        return $stripe->subscriptions->cancel(
            $data['subscription_id'],
            []
        );
    }

    /**
     * @param $data
     * Make 3D secure payment with card
     * Create payment intent
     */
    public function ThreeDPayment($data)
    {
        $stripe = new \Stripe\StripeClient(env('STRIPE_SECRET_KEY'));
        return $stripe->paymentIntents->create([
            'amount' => $data['amount']*100,
            'currency' => $data['currency'],
            'payment_method_types' => ['card'],
            'metadata' => [
                'user_id' => $data['user_id'],
            ],
            'description' => $data['description'],
            'payment_method' => $data['payment_method'],
            'customer' => $data['customer'],
            'confirm' => true
        ]);
    }

    /**
     * @param $data
     * Confirm a payment intent method
     */
    public function confirmPaymentIntent($data)
    {
        $stripe = new \Stripe\StripeClient(env('STRIPE_SECRET_KEY'));
        // Confirm a PaymentIntent
        return $stripe->paymentIntents->confirm(
            $data['id'],
            [
                'payment_method' => $data['payment_method'],
                'return_url' => 'http://127.0.0.1:8000/',
            ]
        );
    }

    /**
     * @param $data
     * Create refund for payment intent
     */
    public function refundPayment($data)
    {
        $stripe = new \Stripe\StripeClient(env('STRIPE_SECRET_KEY'));
        // Create a refund
        return $stripe->refunds->create([
            'payment_intent' => $data['pi_id'],
            'amount' => $data['amount'],
          ]);
    }

}
