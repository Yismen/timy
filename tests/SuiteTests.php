<?php

namespace Dainsys\Timy\Tests;

use Dainsys\Timy\Tests\Feature\DashboardTestTrait;
use Dainsys\Timy\Tests\Feature\TeamsTestsTrait;
use Dainsys\Timy\Tests\Feature\TimyDispositionTestsTrait;
use Dainsys\Timy\Tests\Unit\CloseInactiveTimersCommandTest;

class SuiteTests extends TestCase
{
    use DashboardTestTrait;
    use TimyDispositionTestsTrait;
    use TeamsTestsTrait;
    use CloseInactiveTimersCommandTest;
}
