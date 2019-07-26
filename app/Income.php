<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Income extends Model
{
    protected $guarded = [];

    protected function user()
    {
        return $this->belongsTo(User::class);
    }
}
