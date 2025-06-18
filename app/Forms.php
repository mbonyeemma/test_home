<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Forms extends Model
{
    protected $fillable = ['name', 'form_id'];
    public function fields()
    {
        return $this->hasMany(FormFields::class);
    }
}
