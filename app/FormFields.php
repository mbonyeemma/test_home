<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class FormFields extends Model
{
    protected $fillable = ['forms_id', 'field_type', 'field_label',
    'name', 'field_value', 'option', 'status', 'dropdown_options', 'maker_id', 'checker_id', 'approval_status', 'checked_at'];

    protected $casts = [
        'dropdown_options' => 'array',
         'checked_at' => 'datetime',
    ];

    public function forms()
    {
        return $this->belongsTo(Forms::class);
    }
}
