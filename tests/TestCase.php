<?php

namespace Tests;

use App\Models\Country;
use Database\Seeders\CountrySeeder;
use Database\Seeders\CurrencySeeder;
use Illuminate\Foundation\Testing\TestCase as BaseTestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\Traits\LocalizedTestTrait;
use Illuminate\Support\Facades\App;

abstract class TestCase extends BaseTestCase
{
    use CreatesApplication;
    use LocalizedTestTrait;
    use RefreshDatabase;

    protected $seed = true;
    protected $countrySeeder = CountrySeeder::class;
    protected $currencySeeder = CurrencySeeder::class;

    protected function setUp(): void
    {
        parent::setUp();

        // Configure session and middleware
        $this->withoutExceptionHandling([
            \Illuminate\Auth\AuthenticationException::class,
            \Illuminate\Auth\Access\AuthorizationException::class,
            \Illuminate\Validation\ValidationException::class,
            \Illuminate\Routing\Exceptions\InvalidSignatureException::class
        ]);

        $this->withMiddleware([
            \Illuminate\Session\Middleware\StartSession::class,
            \Illuminate\View\Middleware\ShareErrorsFromSession::class,
            \App\Http\Middleware\VerifyCsrfToken::class,
        ]);

        // Set default locale
        $this->setTestLocale('de');
    }
}
