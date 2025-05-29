<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class ViewComposerServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {
        //$this->composeNavigation();
		//view()->composer("ViewName","App\Http\ViewComposers\MessageViewComposer");
		view()->composer(array('message.inbox', 'message.draft','message.sent', 'message.view', 'message.create', 'message.delete'),"App\Http\ViewComposers\MessageViewComposer");
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
