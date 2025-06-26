<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Forms extends Model
{
    protected $fillable = ['name', 'form_id', 'form_submission_url'];
    public function fields()
    {
        return $this->hasMany(FormFields::class);
    }
}
