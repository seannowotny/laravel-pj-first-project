<?php

namespace Tests\Feature;

use Tests\TestCase;

class HomeTest extends TestCase
{
    public function test_Home_Page_Is_Working_Correctly()
    {
        $response = $this->get('/');

        $response->assertStatus(200);
    }

    public function test_Contact_Page_Is_Working_Correctly()
    {
        $response = $this->get('/contact');

        $response->assertStatus(200);
    }
}
