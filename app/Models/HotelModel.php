<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class HotelModel extends Model
{
    //
    protected $table = 'Hotel';
    protected $fillable = [
        'HotelName',
        'HotelAddress',
        'OpenDay',
        'HotelStatus',
        'locationDistrictId',
    ];

    // public function district()
    // {
    //     return $this->belongsTo(LocationCityModel::class, 'locationDistrictId');
    // }
    ///!!!!!! LÁt về thêm model quận rồi thực hiện việc hiển thị
}
