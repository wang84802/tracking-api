<?php

namespace App\Services;

use Log;
use Exception;
use AfterShip\LastCheckPoint;
use App\Repositories\CheckpointRepository;
use App\Repositories\TrackingRepository;
use App\Formatters\TrackingFormatter;
use App\Services\AftershipService;
use App\Examiners\Examiner;
use App\Exceptions\ValidationFailedException;

class TrackingService {
    public function __construct(TrackingRepository $repository, TrackingFormatter $formatter, AftershipService $aftership,CheckpointRepository $checkpoint) {
        $this->checkpoint = $checkpoint;
        $this->repository = $repository;
        $this->formatter = $formatter;
        $this->aftership = $aftership;
    }

    public function test($mixed_number){
        $tracking = $this->repository->findActiveByMixedNumber($mixed_number);

        Examiner::mixedNumberNotFound($tracking);
        $ols_checkpoints = $this->getOlsCheckpoints($tracking->ols_key);

        $trackings = new LastCheckPoint('aee0c6bf-ae77-4909-82b0-fe94a171e988');
        return $trackings->getById($tracking->aftership_id);
        //return $trackings->get($tracking['slug'],$tracking['tracking_number']);
    }
    public function insert($input) { //try catch
        try {
            $tracking = $this->formatter->request('insert', $input);

            $output = $this->repository->create($tracking)->toArray();

            if (isset($tracking['tracking_number']))
                $this->aftership->createTracking($tracking['ols_key'], $tracking['tracking_number']);

            return $this->formatter->response('insert', $output);
        } catch (\Exception $e) {
            return [
                'status' => 403,
                'data' => [
                    'error' => [
                        'type' => get_class($e),
                        'message' => $e->getMessage()
            ]]];
        }
    }

    public function bulkInsert($trackings) { // try catch

        Examiner::duplicateOlsKey($trackings);
        try{
            foreach ($trackings as $i => $tracking)
                $trackings[$i] = $this->formatter->request('insert', $tracking);
            $success = \DB::table('trackings')->insert($trackings);
        }catch (Exception $e){
            throw new ValidationFailedException(get_Class($e),'400069110', $e->getMessage());
        }

        foreach ($trackings as $tracking)
            if (isset($tracking['tracking_number']))
                $this->aftership->createTracking($tracking['ols_key'], $tracking['tracking_number']);
        return [
            'status' => '200',
            'data' => [
                'message' => 'Inserted trackings successfully.'
        ]];
    }

    public function update($ols_key, $attributes) {

        $tracking = $this->formatter->request('update', $attributes);
        $success = $this->repository->updateActiveBy('ols_key', $ols_key, $tracking); //$column, $value, $attributes
        $output = $this->repository->findActiveBy('ols_key', $ols_key, ['ols_key', 'updated_at'])->toArray();
        $response = $this->formatter->response('update', $output);

        if (isset($tracking['tracking_number']))
            $this->aftership->createTracking($ols_key, $tracking['tracking_number']);

        return $response;
    }

    public function inactivate($ols_key) {

//        /* 20190313 : delete tracking with checkpoints */
//        $trackId = $this->repository->getBy('ols_key',$ols_key,'tracking_id');
//        $this->checkpoint->inactivatedBy('tracking_id',$trackId);
//        //////

        $success = $this->repository->inactivatedBy('ols_key', $ols_key);
        return $this->formatter->response('delete', ['ols_key' => $ols_key]);
    }

    public function select($mixed_number) {

        $tracking = $this->repository->findActiveByMixedNumber($mixed_number);

        Examiner::mixedNumberNotFound($tracking);

        $ols_checkpoints = $this->getOlsCheckpoints($tracking->ols_key);
        $aftership_checkpoints = $this->aftership->getCheckpoints($tracking->aftership_id);
        $checkpoints = array_merge($ols_checkpoints, $aftership_checkpoints);

        $response = $this->formatter->response('select', $tracking);
        $response['data']['checkpoints'] = $checkpoints;

        return $response;
    }
    protected function getOlsCheckpoints($ols_key) {
        $messages = [
            'OlsReceived' => 'Shipment information received',
            'OlsProcessed' => 'Arrival Distribution Center'
        ];
        $checkpoints = $this->repository->findCheckpointsBy('ols_key', $ols_key)->toArray();

        $formatted_checkpoints = [];
        foreach ($checkpoints as $checkpoint) {
                $formatted_checkpoint = [
                    'date' => explode(' ', $checkpoint['checkpoint_time'])[0],
                    'time' => join(':', array_slice(explode(':', explode(' ', $checkpoint['checkpoint_time'])[1]), 0, 2)),
                    'message' => $messages[$checkpoint['checkpoint_status']],
                    'tag' => 'InfoReceived'
                ];
            array_push($formatted_checkpoints, $formatted_checkpoint);
        }
        return $formatted_checkpoints;
    }
}
