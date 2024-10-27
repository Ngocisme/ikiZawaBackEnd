<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LocationDistrictModel extends Model
{
    //
    protected $table = 'Location_district';
    protected $fillable = [
        'locationDistrictName',
        'locationCityId',
    ];

    public function city()
    {
        return $this->belongsTo(LocationCityModel::class, 'locationCityId', 'locationCityId');
    }
}
