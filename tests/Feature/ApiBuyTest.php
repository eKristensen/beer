<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ApiBuyTest extends TestCase
{
    /**
     * A basic feature test example.
     *
     * @return void
     */

    // Test every branch of stuff to check....

    // test purchase works for a product, and sum is updated

    // Test only active products can be purchased
    // Expect 403: error: Product inactive

    // Test only active rooms can buy stuff
    // Expect 403: error: Room inactive

    // Test negative quantities does not allow

    // Test IP shows up as expected and is saved
}
