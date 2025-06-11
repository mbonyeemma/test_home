<?php

namespace App\Repositories;

use App\Models\Message as Message;
use App\Models\MessageRecipient as MessageRecipient;
use \DB as DB;
use Auth;

class MessageRepository
{

    static function inbox()
    {
		//$users->where('user_id', $comment->user_id)->count() 
		$query = "SELECT m.senderid, mr.id as messagereceivedid, m.id, m.subject, mr.recipientid, mr.created_at, u.email, mr.isread, u.name
		FROM messagerecipient as mr 
		INNER JOIN users u ON (mr.recipientid = u.id)
		INNER JOIN message m ON (mr.messageid = m.id)
		WHERE mr.recipientid = '".Auth()->User()->id."' 
		ORDER BY mr.id ASC";
		$inbox = DB::select($query);
        return $inbox;
    }
	static function unread()
    {
		//$users->where('user_id', $comment->user_id)->count() 
		$query = "SELECT *
		FROM messagerecipient as mr 
		WHERE mr.recipientid = '".Auth()->User()->id."' AND isread = 0
		ORDER BY mr.id ASC";
		$inbox = DB::select($query);
        return $inbox;
    }

    static function sent()
    {
       $query = "SELECT *
		FROM message as m 
		WHERE m.senderid = '".Auth()->User()->id."' AND `type` = 1
		ORDER BY m.id ASC";
		$sent = DB::select($query);
        return $sent;
    }
	static function draft()
    {
       $query = "SELECT *
		FROM message as m 
		WHERE m.senderid = '".Auth()->User()->id."' AND `type` = 2
		ORDER BY m.id ASC";
		$draft = DB::select($query);
        return $draft;
    }
	static function deleted(){
	}

}