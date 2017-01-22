<?php

namespace App\Estimates;

use App\Users\User;
use Illuminate\Database\Eloquent\Model as Eloquent;
use Illuminate\Database\Eloquent\SoftDeletes;

class Estimate extends Eloquent
{
    use SoftDeletes;
    protected $dates = ['deleted_at'];

    protected $table = 'estimates';

    protected $guarded = [];

    public function owner()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function getTotalCostAttribute($total_cost)
    {
        $formatter = new \NumberFormatter($this->owner->locale_string, \NumberFormatter::CURRENCY);

        return $formatter->formatCurrency($total_cost, $this->owner->locale).PHP_EOL;
    }

    public function getTotalTimeAttribute($total_time)
    {
        if ($total_time < 1) {
            return '0 Hours';
        }

        $days = floor($total_time / $this->owner->hours_in_day);
        $hours = ceil($total_time - ($days * $this->owner->hours_in_day));

        $dayString = ($days > 0 ? ($days > 1 ? $days.' days' : $days.' day') : '');
        $hoursString = ($hours > 0 ? ($hours > 1 ? $hours.' hours' : $hours.' hours') : ($hours < 1 ? '0 Hours' : ''));

        return $dayString.' '.$hoursString;
    }

    public function recalculateTotals()
    {
        $calculator = new Calculator($this);
        $this->total_cost = floatval($calculator->getTotalCost());
        $this->total_time = $calculator->getTotalHours();
        $this->save();
    }
}
