<?php

namespace Dainsys\Timy\Controllers;

use Dainsys\Timy\App\Resources\TimerResource;
use Dainsys\Timy\App\Timer;
use Dainsys\Timy\App\Repositories\UserDataRepository;

class UserDashboardDataController extends BaseController
{
    public function index(UserDataRepository $repo)
    {
        return response()->json([
            'data' => $repo->toArray()
        ]);
    }
}
