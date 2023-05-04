<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Laravel\Scout\Searchable;

class Meeting extends Model
{
    use HasFactory, Searchable;

    protected $guarded = [];

    protected $casts = ['date' => 'datetime:M d Y H:i'];

    public function citizen()
    {
        return $this->belongsTo(Citizen::class);
    }
    
    public function lawyer()
    {
        return $this->belongsTo(Lawyer::class);
    }

    public function toSearchableArray()
    {
        return [
            'lawyers.first_name' => '',
            'lawyers.last_name' => '',
            'citizens.first_name' => '',
            'citizens.last_name' => '',
            'date' => ''
        ];
    }
}
