<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Checkin extends Model
{
    use SoftDeletes;

    protected $dates = ['deleted_at'];

    public function staff()
    {
        return $this->belongsTo('App\Staff', 'staffId', 'id');
    }
}
