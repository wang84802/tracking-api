<?php

namespace App\Services;

use Log;
use AfterShip\Trackings;
use App\Examiners\Examiner;
use App\Formatters\AftershipFormatter;
use App\Repositories\TrackingRepository;

class AftershipService {
    protected $aftership_api_key = 'aee0c6bf-ae77-4909-82b0-fe94a171e988';
    //protected $aftership_api_key = 'cff20536-3da2-4e92-b36d-8caa9dcbfbc6';

    public function __construct(TrackingRepository $repository, AftershipFormatter $formatter) {
        $this->repository = $repository;
        $this->formatter = $formatter;
    }

    public function createTracking($ols_key, $tracking_number) {
        /* 20190214-Chris : add input "slug" */
        //$courier_slug = $this->repository->findCourierBy('ols_key', $ols_key)->aftership_courier_slug;
        $courier_slug = $this->repository->getValueBy('ols_key',$ols_key,'slug'); //get slug

        //
        $response = $this->remoteCreateTracking($ols_key, $tracking_number,$courier_slug);

        Examiner::aftershipResponse($tracking_number, $response);

        $success = $this->storeResponse($response);
    }
    protected function remoteCreateTracking($ols_key, $tracking_number,$courier_slug) {
        try {
            $trackings = new Trackings($this->aftership_api_key);  //aftership api
            $tracking_info = [
                'title' => $ols_key,
                'slug' => $courier_slug,
                //'tracking_destination_country' => 'GBR'
            ];

            return $trackings->create($tracking_number, $tracking_info);
        } catch (\Exception $e) {
            return $e->getMessage();
        }
    }
    protected function storeResponse($response) {
        $tracking = $response['data']['tracking'];
        $ols_key = $tracking['title'];
        $attributes = $this->formatter->request('update', $tracking);

        $success = $this->repository->updateActiveBy('ols_key', $ols_key, $attributes);
        return $success;
    }

    public function getCheckpoints($aftership_id) {
        if (empty($aftership_id))
            return [];

        $checkpoints = $this->remoteGetTracking($aftership_id);

        $formatted_checkpoints = [];
        foreach ($checkpoints as $checkpoint) {
            $formatted_checkpoint = $this->formatCheckpoint($checkpoint);
            array_push($formatted_checkpoints, $formatted_checkpoint);
        }
        return $formatted_checkpoints;
    }
    protected function remoteGetTracking($aftership_id) {
        $trackings = new Trackings($this->aftership_api_key); //call aftership api to get tracking number
        try{
            return $trackings->getById($aftership_id)['data']['tracking']['checkpoints'];
        } catch( \Exception $e) {
                Examiner::aftershipResponse('Error', $e->getMessage());
        }
    }
    protected function formatCheckpoint($checkpoint) {
        return [
            'date' => explode('T', $checkpoint['checkpoint_time'])[0],
            'time' => join(':', array_slice(explode(':', explode('T', $checkpoint['checkpoint_time'])[1]), 0, 2)),
            'message' => $checkpoint['message'],
            'location' => $checkpoint['location'],
            // 'country' => $checkpoints['country_name'],
            // 'city' => $checkpoints['city'],
            // 'state' => $checkpoints['state'],
            // 'zip' => $checkpoints['zip'],
            'tag' => $checkpoint['tag']
        ];
    }
}
