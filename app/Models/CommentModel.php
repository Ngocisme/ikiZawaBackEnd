<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CommentModel extends Model
{
    //
    protected $table = 'Comment';
    protected $primaryKey = 'CommentId';
    protected $fillable = [
        'CommentId',
        'HotelId',
        'CustomerName',
        'Email',
        'Content',
        'Display',
        'Rating',
    ];

    public function hotel()
    {
        return $this->belongsTo(HotelModel::class, 'HotelId', 'HotelId');
    }
}
