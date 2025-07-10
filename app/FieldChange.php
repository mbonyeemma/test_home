<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class FieldChange extends Model
{
     protected $fillable = [
        'form_field_id', 'form_id', 'maker_id', 'checker_id',
        'action', 'field_data', 'approval_status', 'checked_at',
    ];

    protected $casts = [
        'field_data' => 'array',
        'checked_at' => 'datetime',
    ];

    public function form() { return $this->belongsTo(Forms::class); }
    public function field() { return $this->belongsTo(FormFields::class, 'form_field_id'); }
    public function maker() { return $this->belongsTo(User::class, 'maker_id'); }
    public function checker() { return $this->belongsTo(User::class, 'checker_id'); }
}
