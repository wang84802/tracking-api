<?php

namespace App\Repositories;

use Log;
abstract class Repository {
    public function all($columns = ['*']) {
        return $this->model->all($columns);
    }

    public function create($attributes) {
        return $this->model->create($attributes);
    }

    public function update($id, array $attributes, array $options = []) {
        $instance = $this->model->findOrFail($id);

        return $instance->update($attributes, $options);
    }

    public function updateBy($column, $value, array $attributes = [], array $options = []) {
        return $this->model->where($column, $value)->update($attributes, $options);
    }

    public function updateActiveBy($column, $value, array $attributes = [], array $options = []) {
        return $this->model->where($column, $value)->where('active', 1)->update($attributes, $options);
    }

    public function inactivatedBy($column, $value) {
        return $this->model->where($column, $value)->where('active', 1)->update([ 'active' => 0 ]);
    }

    public function first($columns = ['*']) {
        return $this->model->first($columns);
    }

    public function find($id, $columns = ['*']) {
        return $this->model->find($id, $columns);
    }

    public function findBy($column, $value, $columns = ['*']) {
        return $this->model->where($column, $value)->first($columns);
    }

    public function findActiveBy($column, $value, $columns = ['*']) {
        return $this->model->where($column, $value)->where('active', 1)->first($columns);
    }

    public function get($columns = ['*']) {
        return $this->model->get($columns);
    }

    public function getBy($column, $value, $columns = ['*']) {
        return $this->model->where($column, $value)->value($columns);
    }

    public function destroy($ids) {
        return $this->model->destroy($ids);
    }

    public function destroyBy($column, $value) {
        return $this->model->where($column, $value)->delete();
    }

    public function paginate($perPage = null, $columns = ['*'], $pageName = 'page', $page = null) {
        return $this->model->paginate($perPage, $columns, $pageName, $page);
    }

    public function paginateBy($column, $value, $perPage = null, $columns = ['*'], $pageName = 'page', $page = null) {
        return $this->model->where($column, $value)->paginate($perPage, $columns, $pageName, $page);
    }
    public function getValueBy($column, $value, $columns = ['*']) {
        return $this->model->where($column, $value)->value($columns);
    }
}
