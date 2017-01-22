<?php

namespace App\Http\Requests;

class UpdateUserRequest extends Request
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'name'           => ['required'],
            'email'          => ['required', 'email'],
            'default_rate'   => ['regex:/(\d+(\.\d+)?)/'],
            'locale'         => ['required', 'in:USD,EUR,GBP'],
            'rate_time_unit' => ['required', 'in:hourly,daily'],
            'hours_in_day'   => ['required', 'numeric', 'between:1,24'],
        ];
    }

    public function messages()
    {
        return [
            'name.required'           => 'You must specify your name.',
            'email.required'          => 'You must specify an email address as your point of contact.',
            'email.email'             => 'Please ensure you use a valid email address.',
            'default_rate.regex'      => 'Please make sure your default rate is a valid number, eg: 1, 1.5 etc',
            'locale.required'         => 'Please make sure you pass a locale for your currency.',
            'locale.in'               => 'Please make sure your locales are either USD, EUR or GBP.',
            'rate_time_unit.required' => 'Please make sure you pass your rate time unit.',
            'rate_time_unit.in'       => 'Please make sure your rate time unit is either hourly or daily.',
            'hours_in_day.required'   => 'Please make sure you pass the working hours per day as a number between 1 and 24.',
            'hours_in_day.number'     => 'Please make sure you pass the working hours per day as a number between 1 and 24.',
            'hours_in_day.between'    => 'Please make sure you pass the working hours per day as a number between 1 and 24.',
        ];
    }

    public function response(array $errors)
    {
        return response()->json(['success' => false, 'errors' => $errors], 400);
    }
}
