<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Patient extends Model {
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'title',
        'father',
        'age',
        'mobile',
        'date_of_discharge',
        'narration',
        'bed_number',
        'has_oxygen_line',
        'ward_id',
        'user_id',
        'spo2_level'
    ];

    public function ward() {
        return $this->belongsTo(Ward::class);
    }

    public function user() {
        return $this->belongsTo(User::class);
    }
}