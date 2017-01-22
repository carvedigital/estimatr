<?php

namespace App\Users;

class UserRepository
{
    protected $model;

    public function __construct(User $user)
    {
        $this->model = $user;
    }

    /**
     * @return User
     */
    public function update(User $user, array $data)
    {
        $user->name = $data['name'];
        $user->email = $data['email'];
        $user->company = $data['company'];
        $user->default_rate = floatval($data['default_rate']);
        $user->locale = strtoupper($data['locale']);
        $user->rate_time_unit = strtolower($data['rate_time_unit']);
        $user->hours_in_day = (int) $data['hours_in_day'];
        $user->save();

        return $user;
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
