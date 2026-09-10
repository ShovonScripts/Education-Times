<?php

namespace Tests\Feature;

use Tests\TestCase;

class ExampleTest extends TestCase
{
    public function test_the_application_returns_a_successful_response(): void
    {
        // The app is served from a subdirectory (XAMPP: /ProDo/edtimes/public),
        // so we must expose the subdirectory as SCRIPT_NAME for Symfony to
        // compute a correct path info while matching routes in tests.
        $basePath = rtrim((string) parse_url(config('app.url'), PHP_URL_PATH), '/');

        if ($basePath) {
            $this->withServerVariables([
                'SCRIPT_FILENAME' => $basePath.'/index.php',
                'PHP_SELF' => $basePath.'/index.php',
                'SCRIPT_NAME' => $basePath.'/index.php',
            ]);
        }

        $response = $this->get('/up');

        $response->assertStatus(200);
    }
}
