<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RoomModel extends Model
{
    //
    protected $table = 'Room';
    protected $primaryKey = 'RoomId';
    protected $fillable = [
        'RoomName',
        'HotelId',
        'RoomType',
        'RoomStatus',
        'Description',
        'MaxCustomer',
        'Price'
    ];

    public function hotel()
    {
        return $this->belongsTo(HotelModel::class, 'HotelId', 'HotelId');
    }

    // public function imageRoom()
    // {
    //     return $this->hasMany(HotelImageModel::class, 'HotelId', 'HotelId');
    // }
}
