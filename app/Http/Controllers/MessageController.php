<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;

use Auth;
use Session;
use \App\Models\Message as Message;
use \App\Models\MessageRecipient as MessageRecipient;
class MessageController extends Controller {

    public function __construct() {
        //$this->middleware(['auth', 'clearance'])->except('index', 'show');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

    public function index($type) {
		if($type == 1){
			return view('message.inbox');
		}elseif($type == 2){
			return view('message.sent');
		}elseif($type == 3){
			return view('message.draft');
		}
    }
	

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create() {
       //return view('message.create');
	   $users = \App\Models\User::OrderBy('name')->pluck('name', 'id');
	   return view('message.create', compact('users'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request) { 
		$messageData = [
			'content' => $request->content, // the content of the message
			'senderid' => Auth::user()->id, // Who should receive the message
			'subject' => $request->subject,
		];
		$respients = $request->receivers;
		\DB::beginTransaction(); //Start transaction!
		$message = new Message;
		$message->fill($request->all());
		try{
		   //saving logic here
		   $message->save();
		   foreach($respients as $respient){
			  $messagerecipient = new MessageRecipient;
			  $messagerecipient->messageid = $message->id;
			  $messagerecipient->recipientid = $respient;
			  $messagerecipient->isread = 0;//unread message is flagged 0
			  $messagerecipient->save();
		   		//	Ship::find($ship->id)->captain()->save($captain);
		   }
		}
		catch(\Exception $e)
		{
		  //failed logic here
		   \DB::rollback();
		   throw $e;
		}
		
		\DB::commit();
		return redirect()->route('messages', ['type' => 1]);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id) {
		$messagerecipient = MessageRecipient::findOrFail($id); //Find post of id = $id
        return view ('message.view', compact('messagerecipient'));
       
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id) {
        
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id) {
        
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id) {
       
    }
}