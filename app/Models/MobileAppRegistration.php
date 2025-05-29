<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MobileAppRegistration extends Model
{
    //specify the table to be used to store the contact
    protected $table = "restrackself_reg";
    //specify the fields that must be filled
    protected $fillable = [
        'username',
        'name',
        'email',
        'hubid',
        'password',
        'telephone_number',
        'driving_permit',
        'defensive_driving',
        'bb_training',
        'hep_b_immunisation'
    ];

    public function setPasswordAttribute($password)
	{   
		$this->attributes['password'] = bcrypt($password);
	}
}
