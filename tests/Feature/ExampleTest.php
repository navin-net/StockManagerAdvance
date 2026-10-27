<?php

namespace Tests\Feature;

// use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ExampleTest extends TestCase
{
    /**
     * A basic test example.
     */
    public function test_the_application_returns_a_successful_response(): void
    {
        // The login page reads shop branding even when no shop is configured.
        \Illuminate\Support\Facades\Schema::create('shop_infos', function (\Illuminate\Database\Schema\Blueprint $table) {
            $table->id();
        });
        $response = $this->get('/');

        $response->assertStatus(200);
    }
}
