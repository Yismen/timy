<?php

namespace Dainsys\Timy\Tests;

use Dainsys\Timy\Tests\Feature\ApiRotuesTestTrait;
use Dainsys\Timy\Tests\Feature\DashboardTestTrait;
use Dainsys\Timy\Tests\Unit\TeamsTestsTrait;
use Dainsys\Timy\Tests\Unit\CloseInactiveTimersCommandTest;
use Dainsys\Timy\Tests\Unit\DispositionsTestsTrait;
use Dainsys\Timy\Tests\Unit\ForcedTimersTestsTrait;
use Dainsys\Timy\Tests\Unit\TimeableTestTrait;

class SuiteTests extends TestCase
{
    use ApiRotuesTestTrait;
    use DashboardTestTrait;
    use DispositionsTestsTrait;
    use TeamsTestsTrait;
    use CloseInactiveTimersCommandTest;
    use TimeableTestTrait;
    use ForcedTimersTestsTrait;
    // OpenTimersMonitor
    // RolesManagement
    // TimerControl
    // TimersTable
    // UserHoursInfo
}
