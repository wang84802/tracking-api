<?php

namespace App\Services;

use Log;
use App\Examiners\Examiner;
use App\Formatters\CheckpointFormatter;
use App\Repositories\CheckpointRepository;
use App\Transformers\CheckpointTransformer;
use App\Exceptions\ValidationFailedException;


class CheckpointService {
    public function __construct(CheckpointRepository $repository, CheckpointFormatter $formatter, CheckpointTransformer $transformer) {
        $this->repository = $repository;
        $this->formatter = $formatter;
        $this->transformer = $transformer;
    }

    public function insert($input) {

        // Examiner::CheckpointInvalid($input);

        $transformed_input = $this->transformer->request($input); // with tracking_id
        $checkpoint = $this->formatter->request('insert', $transformed_input);

        $output = $this->repository->create($checkpoint)->toArray();
        $output['ols_key'] = $input['ols_key'];
        return $this->formatter->response('insert', $output);
    }

    public function bulkInsert($checkpoints) {
    
        $responses['status'] = '200';
        $responses['data'] = NULL;
        //try {
            $array_tmp = [];
            foreach ($checkpoints as $checkpoint) {
                // Examiner::CheckpointInvalid($checkpoint);
                $response = $this->insert($checkpoint);
                array_push($array_tmp, $response['data']);
            }
            $responses['data'] = $array_tmp;
        return $responses;
    }

    public function inactivate($id) {
        $success = $this->repository->inactivatedBy('checkpoint_id', $id);
        return $this->formatter->response('delete', ['id' => $id]);
    }
}
