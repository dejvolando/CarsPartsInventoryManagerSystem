<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Car extends Model
{
    protected $table = "cars";

    protected $fillable = [
        'name',
        'registration_number',
        'is_registered'
    ];

    //returns parts that are part of current car
    public function parts(): HasMany
    {
        return $this->hasMany(Part::class, 'car_id');
    }
}
