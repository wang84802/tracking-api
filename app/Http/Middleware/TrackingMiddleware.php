<?php

namespace App\Http\Middleware;

use Log;
use Closure;
use App\Examiners\Examiner;

class TrackingMiddleware {
    protected $rules = [
        'insert' => [
            'ols_key' => 'required|string|unique:trackings,ols_key,null,id,active,1|max:255',
            'sales_record_number' => 'required|string|max:255',
            'ols_service_type' => 'required|string',
            'tracking_number' => 'nullable|unique:trackings,tracking_number,null,id|string|max:50',
            'created_by' => 'required|numeric',
            'updated_by' => 'required|numeric',
            'slug' => 'nullable|string|max:20'
        ],
        'bulk_insert' => [
            '*.ols_key' => 'required|string|unique:trackings,ols_key,null,id,active,1|max:255',
            '*.sales_record_number' => 'required|string|max:255',
            '*.ols_service_type' => 'required|string',
            '*.tracking_number' => 'nullable|unique:trackings,tracking_number,null,id|string|max:50',
            '*.created_by' => 'required|numeric',
            '*.updated_by' => 'required|numeric',
            '*.slug' => 'nullable|string|max:20'
        ],
        'update' => [
            'ols_key' => 'required|string|exists:trackings,ols_key,active,1',
            'sales_record_number' => 'string|max:255',
            'ols_service_type' => 'string|max:50',
            'tracking_number' => 'string|unique:trackings,tracking_number,null,id,active,1|max:50',
            'updated_by' => 'required|numeric'
        ],
        'delete' => [
            'ols_key' => 'required|string|exists:trackings,ols_key,active,1'
        ],
        'select' => [
            'ols_key' => 'required|string|max:255' // Mixed number: ols_key or tracking_number
        ]
    ];

    public function handle($request, Closure $next, $method) {

        $input = $request->input('data');

        if (in_array($method, ['update', 'delete', 'select'])) {
            $ols_key = $request->route()[2]['ols_key'];
            $input = is_array($input) ? array_merge($input, ['ols_key' => $ols_key]) : ['ols_key' => $ols_key];
        }
        Examiner::examineRequests($input, $this->rules[$method]);
        
        return $next($request);
    }
}
