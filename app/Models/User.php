<?php namespace App\Models;
use Laravel\Passport\HasApiTokens;
use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Zizaco\Entrust\Traits\EntrustUserTrait;

class User extends Authenticatable
{
    use HasApiTokens;
    use Notifiable;
	use EntrustUserTrait;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'healthregionid','name', 'email', 'password','hubid','username','ref_lab','facilityid'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];
	
	public function setPasswordAttribute($password)
	{   
		$this->attributes['password'] = bcrypt($password);
	}
	public function roles()
    {
        return $this->belongsToMany('App\models\Role');
    }
}
