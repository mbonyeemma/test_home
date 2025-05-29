<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class HubDataProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {
		view()->composer('*',"App\Http\ViewComposers\AllViewComposer");
    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }
	
}
