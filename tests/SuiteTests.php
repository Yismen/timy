<?php

namespace Dainsys\Timy\Tests;

use Dainsys\Timy\Tests\Feature\ApiRotuesTestTrait;
use Dainsys\Timy\Tests\Feature\DashboardTestTrait;
use Dainsys\Timy\Tests\Unit\TeamsTestsTrait;
use Dainsys\Timy\Tests\Feature\TimyDispositionTestsTrait;
use Dainsys\Timy\Tests\Unit\CloseInactiveTimersCommandTest;
use Dainsys\Timy\Tests\Unit\LivewireDispositionsTestsTrait;
use Dainsys\Timy\Tests\Unit\TimeableTestTrait;

class SuiteTests extends TestCase
{
    use DashboardTestTrait;
    use ApiRotuesTestTrait;
    use LivewireDispositionsTestsTrait;
    use TeamsTestsTrait;
    use CloseInactiveTimersCommandTest;
    use TimeableTestTrait;
}
