<?php

namespace Tests\Traits;

use Illuminate\Support\Facades\App;

trait LocalizedTestTrait
{
    protected function getLocalizedRoute(string $name, array $parameters = []): string
    {
        return route($name, array_merge(['locale' => App::getLocale()], $parameters));
    }

    protected function setTestLocale(string $locale): void
    {
        App::setLocale($locale);
    }
}
