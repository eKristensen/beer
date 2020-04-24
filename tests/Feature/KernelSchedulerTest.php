<?php

namespace Tests\Feature;

use Illuminate\Support\Facades\Artisan;
use Tests\TestCase;

class KernelSchedulerTest extends TestCase
{
    public function testKernelScheduler()
    {
        Artisan::call('schedule:run');
    }
}
