<?php
namespace App\Http\ViewComposers;

use App\Repositories\DataForAllViews as DataForAllViews;
use Illuminate\View\View;

class AllViewComposer {

   /* public function compose(View $view) {
       // $view->with('ViewComposerTestVariable', "Calling with View Composer Provider");
	   view()->composer('*', 'App\Http\ViewComposers\NavComposer');
    }*/
	public function compose(View $view)
    {
        $hub_facilities = MessageRepository::facilities();
		$hub_bikes = MessageRepository::bikes();
		$hub_transporters = MessageRepository::transporters();

        $view->with(compact('hub_facilities','hub_bikes','hub_transporters'));
    }
}