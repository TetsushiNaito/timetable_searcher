<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Validator;
use App\Http\Validators\PollnameVaridator;

class pollnameServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        $validator = $this->app['validator'];
        $validator->resolver( function ( $translator, $data, $rules, $messages ) {
            return new PollnameVaridator( $translator, $data, $rules, $messages );
        });
    }
}
