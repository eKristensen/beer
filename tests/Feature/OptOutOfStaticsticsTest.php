<?php

namespace Tests\Feature;

use App\Room;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class OptOutOfStaticsticsTest extends TestCase
{
    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function testStatisticsCanBeSetToFalse()
    {
        $room = Room::create([
            'id'   => 1,
            'name' => 'Test room',
        ]);

        $this->assertEquals(Room::find($room->id)->statistics, true);

        // Set statistics to false

        $room->statistics = false;
        $room->save();

        // Check that it was saved.
        $this->assertEquals(Room::find($room->id)->statistics, false);
    }
}
