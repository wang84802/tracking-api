<?php

namespace App\Transformers;

use App\Repositories\TrackingRepository;

class CheckpointTransformer {

    function __construct(TrackingRepository $repository) {
        $this->repository = $repository;
    }

    public function request($input) {
        $tracking_id = $this->repository->findActiveBy('ols_key', $input['ols_key'], ['tracking_id'])->tracking_id;
        $input['tracking_id'] = $tracking_id;
        return $input;
    }
}
