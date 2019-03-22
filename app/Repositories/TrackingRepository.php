<?php

namespace App\Repositories;

use App\Models\Tracking;

class TrackingRepository extends Repository {
    protected $model;

    public function __construct(Tracking $model) {
      $this->model = $model;
    }

    public function findCourierBy($column, $value) {
        return $this->findActiveBy($column, $value)->courier;
    }

    public function findActiveByMixedNumber($number, $columns = ['*']) {
        return $this->model->where(function ($query) use ($number) {
            $query->where('ols_key', $number)->orWhere('tracking_number', $number);
        })->where('active', 1)->first($columns);
    }

    public function findCheckpointsBy($column, $value) {
        return $this->findActiveBy($column, $value)->checkpoints;
    }
}
