<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class LookupTypeValue extends Model
{
	protected $table = "lookuptypevalue";

		/**
	* Relationship with districts
	*/
	public function lookuptype()
	{
		return $this->belongsTo('App\Models\LookupType','lookuptypeid','id');
	}
}

