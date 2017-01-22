<?php

namespace App\Estimates;

use App\Users\User;

class EstimateFactory
{
    public static function createFromUi(User $user, array $data)
    {
        $data = [
            'name'         => $data['name'] ?: 'Untitled Estimate',
            'rate'         => ((int) $data['rate'] ? ((int) $data['rate'] >= 0 ? (float) $data['rate'] : 0) : 0),
            'user_id'      => $user->id,
            'hours_in_day' => (int) $user->hours_in_day,
        ];
        if ($user->rate_time_unit == 'daily') {
            $data['rate'] = ((int) $data['rate'] ? ((int) $data['rate'] >= 0 ? (float) $data['rate'] / $data['hours_in_day'] : 0) : 0);
        }


        return Estimate::create($data);
    }
}
