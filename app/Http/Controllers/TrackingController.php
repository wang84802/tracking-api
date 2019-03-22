<?php

namespace App\Http\Controllers;

use Exception;
use Illuminate\Http\Request;
use App\Services\TrackingService;

class TrackingController extends Controller {
    public function __construct(TrackingService $service) {
        $this->service = $service;
    }

    public function store(Request $request) {
        $tracking = $request->input('data');
        return $this->service->insert($tracking);
    }

    public function bulkStore(Request $request) {
        $trackings = $request->input('data');
        return $this->service->bulkInsert($trackings);
    }

    public function update(Request $request, $ols_key) {
        $attributes = $request->input('data');
        
        return $this->service->update($ols_key, $attributes);
    }

    public function destroy($ols_key) {
        return $this->service->inactivate($ols_key);
    }

    public function show($mixed_number) {
        return $this->service->select($mixed_number);
    }

    public function test($mixed_number) {
        return $this->service->test($mixed_number);
    }
}
