<?php

namespace App\Http\Controllers;

use App\Estimates\Estimate;
use App\Users\User;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Support\Facades\Auth;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    /**
     * @var  user The User Object
     */
    protected $user;

    public function __construct()
    {
        $this->user = Auth::user();
    }

    protected function estimateBelongsToUser(Estimate $estimate, User $user)
    {
        return $estimate->user_id === $user->id;
    }
}
