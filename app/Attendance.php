<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Attendance extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'user_id', 'time_in', 'time_out', 'total_hours', 'status', 'created_at', 'updated_at',
    ];

    /**
     * The Attendance belongs to user.
     */
    public function users()
    {
    	return $this->belongsTo('App\User');
    }


}
