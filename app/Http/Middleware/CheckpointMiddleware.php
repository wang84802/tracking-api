<?php

namespace App\Http\Middleware;

use Closure;
use App\Examiners\Examiner;
use Illuminate\Http\Exceptions\HttpResponseException;

class CheckpointMiddleware {
    protected $rules = [
        'insert' => [
            'ols_key' => 'required|string|exists:trackings,ols_key,active,1',
            'status' => 'required|string|max:50',
            'time' => 'required|date',
            'created_by' => 'required|numeric'
        ],
        'bulk_insert' => [
            '*.ols_key' => 'required|string|exists:trackings,ols_key,active,1',
            '*.status' => 'required|string|max:50',
            '*.time' => 'required|date',
            '*.created_by' => 'required|numeric'
        ],
        'delete' => [
            'id' => 'required|exists:checkpoints,checkpoint_id,active,1'
        ]
    ];

    public function handle($request, Closure $next, $method) {
        
        $input = $request->input('data');

        if ($method==='delete')
            $input = [ 'id' => $request->route()[2]['id'] ];

        Examiner::examineRequests($input, $this->rules[$method]);
        return $next($request);
    }
}
