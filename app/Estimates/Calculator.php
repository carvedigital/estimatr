<?php

namespace App\Estimates;

class Calculator
{
    /**
     * @var Estimate
     */
    private $estimate;

    /**
     * @var decimal Rate Per Hour
     */
    private $rate;

    /**
     * @var int Hours Per Day
     */
    private $hoursPerDay;

    /**
     * @var array List of calculated total hours
     */
    private $calculatedItems = [];

    public function __construct(Estimate $estimate)
    {
        $this->estimate = $estimate;
        $this->rate = $estimate->rate;
        $this->hoursPerDay = $estimate->hours_in_day;
        $this->calculateItems();
    }

    public function calculateItems()
    {
        if (! is_null($this->estimate->estimate_data) && $this->estimate->estimate_data) {
            $items = json_decode($this->estimate->estimate_data);
            foreach ($items as $item) {
                $this->calculatedItems[] = $this->getEstimatedHoursForItem($item);
            }
        }
    }

    private function getEstimatedHoursForItem($item)
    {
        return floatval(($item->best + floatval(4 * $item->standard) + $item->worst) / 6);
    }

    public function getTotalHours()
    {
        $hours = 0;
        foreach ($this->calculatedItems as $item) {
            $hours = $hours + $item;
        }

        return ceil($hours);
    }

    public function getReadableTime()
    {
    }

    public function getTotalCost()
    {
        return number_format(($this->getTotalHours() * $this->rate), 2, '.', '');
    }

    public function getReadableCost()
    {
        return number_format(($this->getTotalHours() * $this->rate), 2, '.', ',');
    }
}
