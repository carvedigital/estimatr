<?php

namespace App\Estimates;

use App\Estimates\Validators\Update;
use App\Users\User;

class EstimateRepository
{
    protected $model;

    public function __construct(Estimate $object)
    {
        $this->model = $object;
    }

    /**
     * @return Estimate
     */
    public function update(Estimate $estimate, array $data)
    {
        $updateValidator = new Update($data);
        $cleanedData = $updateValidator->getCleanedData();
        $estimate->name = $cleanedData['name'];
        $estimate->rate = $cleanedData['rate'];
        $estimate->estimate_data = json_encode($cleanedData['items']);

        $estimate->save();
        $estimate->recalculateTotals();

        return $estimate;
    }

    /**
     * @return bool
     */
    public function remove(Estimate $object)
    {
        $object->delete();

        return true;
    }

    /**
     * @return Estimate
     */
    public function getById($id)
    {
        return $this->getNew()->find($id);
    }

    /**
     * @return Estimate
     */
    public function getByUser(User $user)
    {
        return $this->getNew()->where('user_id', '=', $user->id)->get();
    }

    /**
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function getAll()
    {
        return $this->getNew()->all();
    }

    /**
     * @return Estimate
     */
    private function getNew($attributes = [])
    {
        return $this->model->newInstance($attributes);
    }

    /**
     * @return Estimate
     */
    private function getModel()
    {
        return $this->model;
    }
}
