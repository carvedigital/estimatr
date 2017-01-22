<?php

namespace App\Http\Transformers;

use League\Fractal;

class User extends Fractal\TransformerAbstract
{
    public function transform(\App\Users\User $user)
    {
        return [
            'id'             => (int) $user->id,
            'name'           => (string) $user->name,
            'email'          => (string) $user->email,
            'company'        => (string) $user->company,
            'default_rate'   => $user->default_rate,
            'locale'         => (string) $user->locale,
            'rate_time_unit' => (string) $user->rate_time_unit,
            'hours_in_day'   => (int) $user->hours_in_day,
        ];
    }
}
