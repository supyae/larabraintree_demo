<?php

namespace App\Providers;

use Braintree\Configuration;
use Illuminate\Support\ServiceProvider;
use Laravel\Cashier\BraintreeService;
use Laravel\Cashier\Cashier;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        //
//        \Braintree_Configuration::environment(config('services.braintree.environment'));
//        \Braintree_Configuration::merchantId(config('services.braintree.merchant_id'));
//        \Braintree_Configuration::publicKey(config('services.braintree.public_key'));
//        \Braintree_Configuration::privateKey(config('services.braintree.private_key'));

        \Braintree\Configuration::environment(config('services.braintree.environment'));
        \Braintree\Configuration::merchantId(config('services.braintree.merchant_id'));
        \Braintree\Configuration::publicKey(config('services.braintree.public_key'));
        \Braintree\Configuration::privateKey(config('services.braintree.private_key'));

        Cashier::useCurrency('USD', '$');
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }
}
