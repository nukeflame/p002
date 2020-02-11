<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class PayrollParameter extends Model
{
    public function category()
    {
        return $this->belongsTo('App\ParCategories', 'category_id');
    }
    
    public function staff()
    {
        return $this->belongsToMany('App\Staff', 'staff_parameters', 'parameter_id', 'staff_id')->withPivot('amount', 'is_active')->withTimestamps();
    }
}
