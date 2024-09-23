<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserLeave extends Model
{
    protected $guarded = ['id'];
    protected $casts = ['dates' => 'array'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
