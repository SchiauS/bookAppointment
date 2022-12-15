<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Appointment extends Model
{

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'first_name',
        'last_name',
        'email',
        'date',
        'message',
        'user_id',
        'hour_interval',
    ];

    public function user()
    {
        return $this->belongsTo('App\User', 'user_id');
    }
        
}
