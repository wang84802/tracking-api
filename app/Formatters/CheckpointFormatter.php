<?php

namespace App\Formatters;

class CheckpointFormatter extends Formatter {
    protected $chart = [
        ['db' => 'tracking_id', 'api' => 'tracking_id'],
        ['db' => 'checkpoint_status', 'api' => 'status'],
        ['db' => 'checkpoint_time', 'api' => 'time'],
        ['db' => 'created_at', 'api' => 'created_at'],
        ['db' => 'created_by', 'api' => 'created_by'],
        ['db' => 'ols_key', 'api' => 'ols_key'],
        ['db' => 'id', 'api' => 'id'] // db: checkpoint_id, eloquent: id
    ];

    protected $columns = [
        'request' => [
            'insert' => [0, 1, 2, 4]
        ],
        'response' => [
            'insert' => [6, 5, 1, 2, 3],
            'delete' => [6]
        ]
    ];
}
