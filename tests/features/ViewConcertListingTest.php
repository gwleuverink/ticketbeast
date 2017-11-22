<?php

use Illuminate\Foundation\Testing\DatabaseMigrations;
use App\Concert;
use Carbon\Carbon;

class ViewConcertListingTest extends TestCase
{
    use DatabaseMigrations;

    /** @test */
    public function user_can_view_a_published_concert_listing()
    {
        // Arrange
        $concert = factory(Concert::class)->states('published')->create([
            'title' => 'The Red Chord',
            'subtitle' => 'with Animosity and Lethargy',
            'date' => Carbon::parse('December 13, 2016 8:00pm'),
            'ticket_price' => 3250,
            'venue' => 'The Mosh Pit',
            'venue_address' => '123 Example lane',
            'city' => 'Laravel',
            'state' => 'ON',
            'zip' => '17916',
            'additional_information' => 'For tickets, call (555) 555-5555',
        ]);

        // Act
        $this->visit('/concerts/' . $concert->id);

        // Assert
        $this->see('The Red Chord');
        $this->see('with Animosity and Lethargy');
        $this->see('December 13, 2016');
        $this->see('8:00pm');
        $this->see('32.50');
        $this->see('The Mosh Pit');
        $this->see('123 Example lane');
        $this->see('Laravel, ON 17916');
        $this->see('For tickets, call (555) 555-5555');
    }

    /** @test */
    public function user_cannot_view_unpublished_concert_listings()
    {
        // Arrange
        $concert = factory(Concert::class)->states('unpublished')->create();

        // Act
        $this->get('/concerts/' . $concert->id);

        // Assert
        $this->assertResponseStatus(404);
    }
}
