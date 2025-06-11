<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ApprovalSetting extends Model
{
    protected $table = 'approval_settings';
    protected $fillable = ['no_of_approval'];
}
