<?php

namespace Tests\Feature;

use Tests\TestCase;

class KernelSchedulerTest extends TestCase
{
    public function testKernelScheduler()
    {
        Artisan::call('schedule:run');
    }
}
