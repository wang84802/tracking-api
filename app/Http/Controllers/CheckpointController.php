<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use GuzzleHttp\Client;
use App\Services\CheckpointService;

class CheckpointController extends Controller {
    public function __construct(CheckpointService $service) {
        $this->service = $service;
    }

    public function store(Request $request) {
        $checkpoint = $request->input('data');

        return $this->service->insert($checkpoint);
    }

    public function bulkStore(Request $request) {
        $checkpoints = $request->input('data');

        return $this->service->bulkInsert($checkpoints);
    }

    public function destroy($id) {
        return $this->service->inactivate($id);
    }
}
