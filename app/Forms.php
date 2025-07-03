<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Forms extends Model
{
    protected $fillable = ['name', 'form_id', 'form_submission_url','publish_status','submitted_by',
    'approved_by'];
    public function fields()
    {
        return $this->hasMany(FormFields::class);
    }
    public function maker()
    {
        return $this->belongsTo(User::class, 'submitted_by');
    }

    public function checker()
    {
        return $this->belongsTo(User::class, 'approved_by');
    }
}
