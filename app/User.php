<?php namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Zizaco\Entrust\Traits\EntrustUserTrait;
use Inani\Messager\Helpers\MessageAccessible;
use Inani\Messager\Helpers\TagsCreator;
class User extends Authenticatable
{
    use Notifiable;
	use EntrustUserTrait;
	use MessageAccessible, TagsCreator;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password','hubid','username','facilityid','ref_lab'
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
    public function organisation()
    {
        return $this->belongsTo('App\Models\Organization');
    }
}
