<?php

namespace Dainsys\Timy\Controllers\Api;

use Dainsys\Timy\Resources\TimerResource;
use Dainsys\Timy\Timer;
use Dainsys\Timy\Repositories\UserDataRepository;

class UserDashboardDataController extends BaseController
{
    public function index()
    {
        return response()->json([
            'data' => UserDataRepository::toArray(auth()->user()),
            'user' => auth()->user()
        ]);
    }
}
