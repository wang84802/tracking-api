<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Tracking extends Model {
    protected $table = 'api_token';

    protected $fillable = [
        'service',
        'token'
    ];


}
