<?php

namespace Dainsys\Timy\Http\Controllers\Api;

use Dainsys\Timy\Http\Controllers\BaseController;
use Dainsys\Timy\Repositories\TimersRepository;
use Dainsys\Timy\Resources\TimerDownloadResource;

class TimerController extends BaseController
{
    public function filtered()
    {
        return TimerDownloadResource::collection(
            TimersRepository::filtered()
        );
    }
}
