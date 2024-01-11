<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\{CustomerController, WebhookController};
use App\Models\User;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

// Route::get('/', function () {
//     $users = User::all();
//     return view('welcome', compact('users'));
// });

Route::get('/', [CustomerController::class, 'index']);

Route::post('/customer/create', [CustomerController::class, 'storeCustomer'])->name('customer-create');

Route::post('/card/add', [CustomerController::class, 'storeCard'])->name('card-create');

Route::post('/price/create', [CustomerController::class, 'storePrice'])->name('price-create');

Route::get('/attach-pm', [CustomerController::class, 'attachPm'])->name('attach-pm');

Route::get('/subscription', [CustomerController::class, 'subscription'])->name('create-subscription');

Route::get('/cancel-subscription', [CustomerController::class, 'cancelSubscription'])->name('cancel-subscription');

Route::get('/3d-secure-payment', [CustomerController::class, 'threeDSecurePayment'])->name('3d-secure-payment');

Route::get('/refund', [CustomerController::class, 'refund'])->name('refund');




