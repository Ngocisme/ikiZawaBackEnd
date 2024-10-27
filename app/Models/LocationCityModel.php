<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LocationCityModel extends Model
{
    //
    protected $table = 'Location_city';

    protected $fillable = [
        'locationCityId',
        'locationCityName'
    ];
}
