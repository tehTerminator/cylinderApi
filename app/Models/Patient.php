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
        'bed_id',
        'ward_id',
    ];

    public function bed() {
        return $this->hasOne(Bed::class);
    }

    public function ward() {
        return $this->hasOne(Ward::class);
    }
}