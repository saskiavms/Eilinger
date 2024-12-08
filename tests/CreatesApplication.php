<?php

namespace Tests;

use Illuminate\Contracts\Console\Kernel;
use Illuminate\Foundation\Application;

trait CreatesApplication
{
    /**
     * Creates the application.
     *
     * @return Application
     */
    public function createApplication()
    {
        $app = require __DIR__.'/../bootstrap/app.php';

        $app->make(Kernel::class)->bootstrap();

        return $app;
    }

    protected function setUp(): void
    {
        parent::setUp();

        // Manually create a dummy vite manifest if needed
        if (!file_exists(public_path('build/manifest.json'))) {
            @mkdir(public_path('build'), 0755, true);
            file_put_contents(public_path('build/manifest.json'), json_encode([]));
        }
    }
}
