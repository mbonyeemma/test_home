<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class UntrackedPackage extends Model {
	protected $table = "untracked_packages";
	//specify the fields that must be filled
    protected $fillable = [
        'barcode','facilityid','hubid'
    ];
	
	public static $rules = [
		'hubid' => 'hubid'
	];
		
}
