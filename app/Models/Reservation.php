<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Reservation extends Model
{
    protected $fillable = [
        'user_id',
        'hall_id',
        'date',
        'reserved_from',
        'reserved_to',
        'subject',
        'total_price',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function hall()
    {
        return $this->belongsTo(Hall::class);
    }
}
