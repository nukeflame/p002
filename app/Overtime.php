<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Overtime extends Model
{
    public function staff()
    {
        return $this->belongsTo('App\Staff');
    }
}
