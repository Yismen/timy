<?php

namespace Dainsys\Timy\Tests;

use Dainsys\Timy\Tests\Feature\DashboardTest;
use Dainsys\Timy\Tests\Feature\Traits\DashboardTestTrait;
use Dainsys\Timy\Tests\Feature\Traits\TeamsTestsTrait;
use Dainsys\Timy\Tests\Feature\Traits\TimyDispositionTestsTrait;

class SuiteTests extends TestCase
{
    use DashboardTestTrait;
    use TimyDispositionTestsTrait;
    use TeamsTestsTrait;
}
