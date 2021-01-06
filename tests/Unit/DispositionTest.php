<?php

namespace Dainsys\Timy\Tests\Unit;

use Dainsys\Timy\Models\Disposition;
use Dainsys\Timy\Tests\TestCase;
use Illuminate\Database\Eloquent\Collection;

class DispositionTest extends TestCase
{
    /** @test */
    public function it_loads_relationships()
    {
        $disposition = Disposition::factory()->create();

        $disposition->with(['timers'])->first();

        $this->assertInstanceOf(Collection::class, $disposition->timers);
    }
}
