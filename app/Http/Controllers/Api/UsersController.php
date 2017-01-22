<?php

namespace App\Http\Controllers\Api;

use App\Http\Requests\UpdateUserRequest;
use App\Http\Transformers\User;
use App\Users\UserRepository;
use Sorskod\Larasponse\Larasponse;
use Illuminate\Http\Request;

class UsersController extends BaseController
{
    /**
     * @var Request request
     */
    private $request;

    /**
     * @var Larasponse
     */
    private $response;
    /**
     * @var UserRepository
     */
    private $userRepository;

    public function __construct(Request $request, Larasponse $response, UserRepository $userRepository)
    {
        parent::__construct();
        $this->request = $request;
        $this->response = $response;
        $this->userRepository = $userRepository;
    }

    public function getUser()
    {
        return $this->response->item($this->user, new User());
    }

    public function updateUser(UpdateUserRequest $updateUserRequest)
    {
        $user = $this->userRepository->update($this->user, $this->request->only([
            'name', 'email', 'company', 'default_rate', 'locale', 'rate_time_unit', 'hours_in_day',
        ]));

        return $this->respondSuccess();
    }
}
