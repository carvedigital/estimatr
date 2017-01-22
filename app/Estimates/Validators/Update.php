<?php

namespace App\Estimates\Validators;

use App\Exceptions\EstimateValidationException;

class Update
{
    private $input;

    private $requiredItemKeys = ['id', 'name', 'best', 'worst', 'standard'];

    public function __construct($input)
    {
        $this->input = $input;
    }

    public function getCleanedData()
    {
        $this->validateName();
        $this->validateRate();
        $this->validateItems();

        return $this->input;
    }

    protected function validateItems()
    {
        foreach ($this->input['items'] as $key => $item) {
            $this->validateItem($item);
            $this->input['items'][$key] = [
                'id'       => $item['id'],
                'name'     => $item['name'],
                'best'     => (float) $item['best'],
                'worst'    => (float) $item['worst'],
                'standard' => (float) $item['standard'],
            ];
        }
    }

    protected function validateItem(array $item)
    {
        foreach ($this->requiredItemKeys as $key) {
            if (! isset($item[$key])) {
                throw new EstimateValidationException('The "'.$key.'" key was not set on estimate item ID: "'.$item['id'].'".');
            }
        }
    }

    protected function validateRate()
    {
        if (! isset($this->input['rate']) || (int) $this->input['rate'] < 0) {
            throw new EstimateValidationException('A rate is required for this estimate.');
        }
    }

    protected function validateName()
    {
        if (! isset($this->input['name']) || ! $this->input['name']) {
            throw new EstimateValidationException('A name is required for this estimate.');
        }
    }
}
