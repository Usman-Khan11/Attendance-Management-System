<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserAttendence extends Model
{
    protected $guarded = ['id'];

    const STATUS = [
        0 => 'Absent',
        1 => 'Present',
        2 => 'Public Holiday',
        3 => 'Leave',
        4 => 'Rest Day',
        5 => 'Markout Missing'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
