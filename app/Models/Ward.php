<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Ward extends Model {
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'title'
    ];

    public function beds() {
        return $this->hasMany(Bed::class);
    }
}