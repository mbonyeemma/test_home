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
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'users';

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
		// If the password is already hashed (starts with $2y$), store it directly
		if (strpos($password, '$2y$') === 0) {
			$this->attributes['password'] = $password;
		} else {
			// Otherwise, bcrypt the password
			$this->attributes['password'] = bcrypt($password);
		}
	}
	public function roles()
    {
        return $this->belongsToMany('App\Models\Role');
    }
    public function organisation()
    {
        return $this->belongsTo('App\Models\Organization');
    }
}
