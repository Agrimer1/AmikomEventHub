<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ExampleTest extends TestCase
{
    use RefreshDatabase;
    /**
     * A basic test example.
     */
    public function test_the_application_returns_a_successful_response(): void
    {
        \App\Models\Partner::create([
            'name' => 'Gojek Indonesia',
            'logo_url' => 'logo.png'
        ]);

        $response = $this->get('/');

        $response->assertStatus(200);
        $response->assertSee('Gojek Indonesia');
    }
}
