<?php

namespace App\Formatters;

class TrackingFormatter extends Formatter {
    protected $chart = [
        ['db' => 'ols_key', 'api' => 'ols_key'],
        ['db' => 'sales_record_number', 'api' => 'sales_record_number'],
        ['db' => 'ols_service_type', 'api' => 'ols_service_type'],
        ['db' => 'tracking_number', 'api' => 'tracking_number'],
        ['db' => 'created_at', 'api' => 'created_at'],
        ['db' => 'created_by', 'api' => 'created_by'],
        ['db' => 'updated_at', 'api' => 'updated_at'],
        ['db' => 'updated_by', 'api' => 'updated_by'],
        /* 20190214-Chris : change input "slug" */
        ['db' => 'slug', 'api' => 'slug'],
        ['db' => 'ols_courier', 'api' => 'ols_courier'],
        ['db' => 'aftership_id','api' => 'aftership_id']
        //
    ];

    protected $columns = [
        'request' => [
            'insert' => [0, 1, 2, 3, 5, 8, 9],
            /* 20190214-Chris : change input "slug" */
            //'update' => [1, 2, 3, 7]
            'update' => [1, 2, 3, 7, 8, 9]
            //
        ],
        'response' => [
            'insert' => [0, 4],
            'update' => [0, 6],
            'delete' => [0],
            'select' => [0, 1, 3, 8, 9, 10]
        ]
    ];
}
