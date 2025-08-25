<?php

namespace App;

use App\Models\Facility;
use Illuminate\Database\Eloquent\Model;

class Forms extends Model
{
    protected $fillable = ['name', 'form_id', 'facility_id', 'form_submission_url', 'publish_status', 'submitted_by', 'approved_by', 'color'];
    
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
    public function facility()
{
    return $this->belongsTo(Facility::class);
}
}
