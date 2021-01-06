<?php

namespace Dainsys\Timy\Tests\Unit;

use Dainsys\Timy\Models\Disposition;
use Dainsys\Timy\Tests\TestCase;
use Illuminate\Database\Eloquent\Collection;

class DispositionFactoryTest extends TestCase
{
    /** @test */
    public function it_creates_an_instance_of_disposition_model()
    {
        $disposition = Disposition::factory()->create();

        $this->assertInstanceOf(Disposition::class, $disposition);
    }
    /** @test */
    public function it_creates_a_payable_disposition()
    {
        $disposition = Disposition::factory()->payable()->create();

        $this->assertEquals(true, $disposition->payable);
    }
    /** @test */
    public function it_creates_a_non_payable_disposition()
    {
        $disposition = Disposition::factory()->notPayable()->create();

        $this->assertEquals(false, $disposition->payable);
    }
    /** @test */
    public function it_creates_a_invoiceable_disposition()
    {
        $disposition = Disposition::factory()->invoiceable()->create();

        $this->assertEquals(true, $disposition->invoiceable);
    }
    /** @test */
    public function it_creates_a_non_invoiceable_disposition()
    {
        $disposition = Disposition::factory()->notInvoiceable()->create();

        $this->assertEquals(false, $disposition->invoiceable);
    }
}
