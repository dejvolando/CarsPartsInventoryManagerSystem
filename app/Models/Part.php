<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Part extends Model
{
    protected $table = "parts";

    protected $fillable = [
        'name',
        'serial_number',
        'car_id'
    ];

    //returns car of which current car part is part of
    public function car(): BelongsTo
    {
        return $this->belongsTo(Car::class, 'car_id');
    }
}
