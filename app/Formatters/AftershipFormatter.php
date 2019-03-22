<?php

namespace App\Formatters;

class AftershipFormatter extends Formatter {
    protected $chart = [
        ['db' => 'aftership_id', 'api' => 'id'],
        ['db' => 'slug', 'api' => 'slug'],
        ['db' => 'registered_at', 'api' => 'created_at']
    ];

    protected $columns = [
        'request' => [
            'update' => [0, 1, 2]
        ],
        'response' => [
        ]
    ];
}
