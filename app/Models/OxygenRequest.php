<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OxygenRequest extends Model {

    protected $table = 'oxygen_requests';
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'patient_id',
        'ward_id',
        'bed_number',
        'spo2_level',
        'state',
        'comment'
    ];

    public function ward() {
        return $this->belongsTo(Ward::class);
    }

    public function patient() {
        return $this->belongsTo(Patient::class);
    }
}