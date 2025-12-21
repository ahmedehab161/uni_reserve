<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Hall extends Model
{
    protected $fillable = [
        'hall_name',
        'hall_capacity',
        'date',
        'start_time',
        'end_time',
        'price_per_hour',
        'booked',
    ];

    protected $casts = [
        'booked' => 'boolean',
    ];

    public function reservations()
    {
        return $this->hasMany(Reservation::class);
    }
}
