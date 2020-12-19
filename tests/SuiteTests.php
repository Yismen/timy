<?php

namespace Dainsys\Timy\Tests;

use Dainsys\Timy\Tests\Feature\ApiRotuesTestTrait;
use Dainsys\Timy\Tests\Feature\ApiTimersFilteredTrait;
use Dainsys\Timy\Tests\Feature\DashboardTestTrait;
use Dainsys\Timy\Tests\Unit\TeamsTestsTrait;
use Dainsys\Timy\Tests\Unit\CloseInactiveTimersCommandTest;
use Dainsys\Timy\Tests\Unit\DispositionsTestsTrait;
use Dainsys\Timy\Tests\Unit\ForcedTimersTestsTrait;
use Dainsys\Timy\Tests\Unit\OpenTimersMonitorTestsTrait;
use Dainsys\Timy\Tests\Unit\RolesManagementTestsTrait;
use Dainsys\Timy\Tests\Unit\TimeableTestTrait;
use Dainsys\Timy\Tests\Unit\TimerControlTestsTrait;
use Dainsys\Timy\Tests\Unit\TimersTableTestsTrait;
use Dainsys\Timy\Tests\Unit\UserHoursInfoTestsTrait;

class SuiteTests extends TestCase
{
    use ApiTimersFilteredTrait;
    use ApiRotuesTestTrait;
    use DashboardTestTrait;
    use DispositionsTestsTrait;
    use TeamsTestsTrait;
    use CloseInactiveTimersCommandTest;
    use TimeableTestTrait;
    use ForcedTimersTestsTrait;
    use OpenTimersMonitorTestsTrait;
    use RolesManagementTestsTrait;
    use TimersTableTestsTrait;
    use UserHoursInfoTestsTrait;
    use TimerControlTestsTrait;
}
