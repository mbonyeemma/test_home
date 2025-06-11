<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class TestType extends Model {
	protected $table = "testtypes";
	//specify the fields that must be filled
    protected $fillable = [
        'name', 'ref_lab'
    ];
}
