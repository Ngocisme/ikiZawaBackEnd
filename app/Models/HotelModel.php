<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class HotelModel extends Model
{
    //
    protected $table = 'Hotel';
    protected $primaryKey = 'HotelId';
    protected $fillable = [
        'HotelName',
        'HotelAddress',
        'OpenDay',
        'HotelStatus',
        'locationDistrictId',
    ];

    public function district()
    {
        return $this->belongsTo(LocationDistrictModel::class, 'locationDistrictId', 'locationDistrictId');
    }

    public function imageHotel()
    {
        return $this->hasMany(HotelImageModel::class, 'HotelId', 'HotelId');
    }
}
