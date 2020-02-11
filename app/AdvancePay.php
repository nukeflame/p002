<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class AdvancePay extends Model
{
    public function staff()
    {
        return $this->belongsTo('App\Staff');
    }
}
