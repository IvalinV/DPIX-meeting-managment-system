<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Meeting extends Model
{
    use HasFactory;

    protected $guarded = [];

    protected $casts = ['date' => 'datetime:M d Y H:i'];

    public function citizen()
    {
        return $this->belongsTo(Citizen::class);
    }
}
