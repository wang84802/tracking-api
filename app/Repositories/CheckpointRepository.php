<?php

namespace App\Repositories;

use App\Models\Checkpoint;

class CheckpointRepository extends Repository {
    protected $model;

    public function __construct(Checkpoint $model) {
      $this->model = $model;
    }
}
