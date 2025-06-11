<?php
namespace App\Http\Controllers;

use App\Mail\ExceptionReports;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use App\Http\Controllers\Controller;

class NotificationsController  extends Controller
{
    /**
     * Ship the given order.
     *
     * @param  Request  $request
     * @param  int  $orderId
     * @return Response
     */
    public function facilitiesNotVisited()
    {
       $user = \App\Models\User::findOrFail(3);

        // Ship order...

       // Mail::to($request->user())->send(new OrderShipped($order));
	   Mail::to('gwmubiru@gmail.com')
			->send(new ExceptionReports());
    }
}