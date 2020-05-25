<?php

namespace Dainsys\Timy\Controllers;

use Dainsys\Timy\Resources\TimerResource;
use Dainsys\Timy\Models\Timer;
use Dainsys\Timy\Repositories\UserDataRepository;

class UserDashboardDataController extends BaseController
{
    public function index(UserDataRepository $repo)
    {
        return response()->json([
            'data' => $repo->toArray()
        ]);
    }
}
