<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserSchedule extends Model
{
    protected $guarded = ['id'];
    protected $casts = ['restday' => 'array'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
