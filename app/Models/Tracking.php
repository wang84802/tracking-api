<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Tracking extends Model {
    protected $table = 'trackings';

    protected $fillable = [
        'ols_key',
        'ols_service_type',
        'sales_record_number',
        'tracking_number',
        'aftership_id',
        'slug',
        'ols_courier',
        'registered_at',
        'created_by',
        'updated_by'
    ];

    public function courier() {
        return $this->belongsTo('App\Models\Courier', 'ols_service_type', 'ols_service_type');
    }

    public function checkpoints() {
        return $this->hasMany('App\Models\Checkpoint', 'tracking_id', 'tracking_id');
    }
}
