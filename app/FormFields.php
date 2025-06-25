<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class FormFields extends Model
{
    protected $fillable = ['form_id', 'field_type', 'field_name', 'field_value', 'option', 'status', 'dropdown_options'];

    protected $casts = [
        'dropdown_options' => 'array',
    ];

    public function forms()
    {
        return $this->belongsTo(Forms::class);
    }
}
