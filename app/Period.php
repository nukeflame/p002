<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Period extends Model
{
    public function staff()
    {
        return $this->belongsToMany('App\Staff', 'staff_periods');
    }
}
