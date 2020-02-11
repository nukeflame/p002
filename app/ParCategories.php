<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ParCategories extends Model
{
    public function parameters()
    {
        return $this->hasMany('App\PayrollParameter');
    }
}
