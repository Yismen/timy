<?php

namespace Dainsys\Timy\Tests;

use Dainsys\Timy\Tests\Feature\ApiRotuesTestTrait;
use Dainsys\Timy\Tests\Feature\DashboardTestTrait;
use Dainsys\Timy\Tests\Unit\TeamsTestsTrait;
use Dainsys\Timy\Tests\Feature\TimyDispositionTestsTrait;
use Dainsys\Timy\Tests\Unit\CloseInactiveTimersCommandTest;
use Dainsys\Timy\Tests\Unit\TimeableTestTrait;

class SuiteTests extends TestCase
{
    use ApiRotuesTestTrait;
    use DashboardTestTrait;
    use TimyDispositionTestsTrait;
    use TeamsTestsTrait;
    use CloseInactiveTimersCommandTest;
    use TimeableTestTrait;
}
