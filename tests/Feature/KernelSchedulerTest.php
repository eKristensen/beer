<?php

namespace Tests\Feature;

use Illuminate\Support\Facades\Artisan;
use Tests\TestCase;

class KernelSchedulerTest extends TestCase
{
    public function testKernelScheduler()
    {
        // Check that exit code for artisan is 0
        // "0" means no errors, success
        $this->assertEquals(Artisan::call('schedule:run'),0);
    }
}
