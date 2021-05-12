<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Bed extends Model {
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'bed_number',
        'ward_id',
        'has_line',
        'patient_id'
    ];

    public function ward(){
        return $this->hasOne(Ward::class);
    }

    public function patient() {
        return $this->hasOne(Patient::class);
    }
}