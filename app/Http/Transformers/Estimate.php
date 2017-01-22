<?php

namespace App\Http\Transformers;

use League\Fractal;

class Estimate extends Fractal\TransformerAbstract
{
    public function transform(\App\Estimates\Estimate $estimate)
    {
        return [
            'id'         => (int) $estimate->id,
            'name'       => $estimate->name,
            'rate'       => (int) $estimate->rate,
            'total_time' => $estimate->total_time,
            'total_cost' => (float) $estimate->total_cost,
            'items'      => json_decode($estimate->estimate_data, true),
        ];
    }
}
