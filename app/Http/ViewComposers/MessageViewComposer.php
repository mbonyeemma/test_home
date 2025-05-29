<?php
namespace App\Http\ViewComposers;

use App\Repositories\MessageRepository as MessageRepository;
use Illuminate\View\View;

class MessageViewComposer {

   /* public function compose(View $view) {
       // $view->with('ViewComposerTestVariable', "Calling with View Composer Provider");
	   view()->composer('*', 'App\Http\ViewComposers\NavComposer');
    }*/
	public function compose(View $view)
    {
        $unread_inbox = MessageRepository::unread();
		$inbox = MessageRepository::inbox();
        $sent = MessageRepository::sent();
		$draft = MessageRepository::draft();
		$deleted = MessageRepository::deleted();

        $view->with(compact('inbox','unread_inbox', 'sent', 'draft', 'deleted'));
    }
}