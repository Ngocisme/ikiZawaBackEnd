<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;

class UserModel extends Authenticatable
{
    //
    use Notifiable;

    protected $table = 'User';
    protected $primaryKey = 'UserId';
    protected $fillable = ['HotelId', 'UserName', 'FullName', 'UserStatus', 'Role'];
    protected $hidden = ['PassWord'];
}
