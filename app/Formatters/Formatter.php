<?php


namespace App\Formatters;

use Log;
class Formatter {
    protected $chart;
    protected $columns;
    protected $status = [

        'insert' => 201,
        'update' => 204,
        'delete' => 204,
        'select' => 200
    ];

    public function request($method, $input) {
        $formatted_input = [];
        foreach ($this->columns['request'][$method] as $index) {
            $key = $this->chart[$index]['api'];
            if (isset($input[$key])) {
                $formatted_key = $this->chart[$index]['db'];
                $formatted_input[$formatted_key] = $input[$key];
            }
        }
        return $formatted_input;
    }

    public function response($method, $output) {
        $formatted_output = [];

        foreach ($this->columns['response'][$method] as $index) {
            $key = $this->chart[$index]['db'];

            if (isset($output[$key])) {
                $formatted_key = $this->chart[$index]['api'];
                $formatted_output[$formatted_key] = $output[$key];
            }
        }
        return [
            'status' => $this->status[$method],
            'data' => $formatted_output
        ];
    }
}
