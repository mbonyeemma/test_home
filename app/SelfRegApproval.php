<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class SelfRegApproval extends Model
{
    protected $fillable = ['self_reg_id', 'approved_by'];
}
