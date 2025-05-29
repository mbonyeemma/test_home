<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class PackageReceipt extends Model {
	protected $table = "packagerecipt";
	protected $fillable = [
        'packageid', 'packagetype','received_by','previous_status','numberofsamples','created_by'
    ];	
}
