<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class hall extends Model
{
    public function reservations()
    {
        return $this->hasMany(Reservation::class);
    }
}
