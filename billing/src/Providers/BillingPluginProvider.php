<?php

namespace Boy132\Billing\Providers;

use App\Models\Role;
use Illuminate\Support\ServiceProvider;
use Stripe\StripeClient;

class BillingPluginProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->bind(StripeClient::class, fn () => new StripeClient(config('billing.secret')));

        Role::registerCustomDefaultPermissions('customer');
        Role::registerCustomDefaultPermissions('product');
    }

    public function boot(): void {}
}
