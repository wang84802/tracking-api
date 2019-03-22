<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Checkpoint extends Model {
    protected $table = 'checkpoints';

    protected $fillable = [
        'tracking_id',
        'checkpoint_status',
        'checkpoint_time',
        'created_by'
    ];
}
