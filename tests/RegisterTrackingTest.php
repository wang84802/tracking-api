<?php

use Laravel\Lumen\Testing\DatabaseMigrations;
use Laravel\Lumen\Testing\DatabaseTransactions;

class RegisterTrackingTest extends TestCase
{
  /**
   * A basic test example.
   *
   * @return void
   */
  public function test()
  {
    $rawData = [
          'olsKey'=>'dev-key-101',
          'salesRecordNumber'=>'SRN0001',
          'clientName'=>'client',
          'trackingNumber'=>'9400110899637012040376',
          'checkpoints'=>[[
            'couriers'=>'GM',
            'countryName'=>'US',
            'message'=>'msg',
            'state'=>'state',
            'zip'=>'zip',
            'checkpointStatus'=>'OlsReceived',
            'checkpointTime'=>'2017-03-20 15=>59:00',
            'overwrite'=>false
          ]]
        ];
    $dbData = [
            // 'ols_tracking_id'=>'43fac531-9483-406d-b20e-05ae05e271b4',
            'ols_key'=>'dev-key-101',
            'sales_record_number'=>'SRN0001',
            'tracking_number'=>'9400110899637012040376',
            // 'create_at'=>'2017-04-12T03:23:02.800Z',
            // 'update_at'=>'2017-04-12T03:23:02.800Z',
            // 'is_deleted'=>false,
            // 'aftership_tracking_id'=>null
        ];
    $this->json('POST', '/registerTracking', ['trackings'=> $rawData])
          ->seeJson(['rData' => $rawData])
          ->seeJson(['olsTracking' => $dbData])
          ;

  }
}
