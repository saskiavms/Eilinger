<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ApplicationEditingTranslationTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function application_editing_restriction_translations_exist_in_german()
    {
        // Set locale to German
        app()->setLocale('de');

        // Test all translation keys exist and return expected content
        $hint = __('application.edit_restriction_hint');
        $warning = __('application.edit_restriction_warning');
        $error = __('application.edit_restriction_error');

        $this->assertEquals('Hinweis:', $hint);
        $this->assertStringContainsString('genehmigt', $warning);
        $this->assertStringContainsString('genehmigt', $error);

        // Ensure translations are not just returning the key
        $this->assertNotEquals('application.edit_restriction_hint', $hint);
        $this->assertNotEquals('application.edit_restriction_warning', $warning);
        $this->assertNotEquals('application.edit_restriction_error', $error);
    }

    /** @test */
    public function application_editing_restriction_translations_exist_in_english()
    {
        // Set locale to English
        app()->setLocale('en');

        // Test all translation keys exist and return expected content
        $hint = __('application.edit_restriction_hint');
        $warning = __('application.edit_restriction_warning');
        $error = __('application.edit_restriction_error');

        $this->assertEquals('Notice:', $hint);
        $this->assertStringContainsString('approved', $warning);
        $this->assertStringContainsString('approved', $error);

        // Ensure translations are not just returning the key
        $this->assertNotEquals('application.edit_restriction_hint', $hint);
        $this->assertNotEquals('application.edit_restriction_warning', $warning);
        $this->assertNotEquals('application.edit_restriction_error', $error);
    }

    /** @test */
    public function translations_are_different_between_languages()
    {
        // Test German
        app()->setLocale('de');
        $germanHint = __('application.edit_restriction_hint');
        $germanWarning = __('application.edit_restriction_warning');
        $germanError = __('application.edit_restriction_error');

        // Test English
        app()->setLocale('en');
        $englishHint = __('application.edit_restriction_hint');
        $englishWarning = __('application.edit_restriction_warning');
        $englishError = __('application.edit_restriction_error');

        // Ensure translations are actually different
        $this->assertNotEquals($germanHint, $englishHint);
        $this->assertNotEquals($germanWarning, $englishWarning);
        $this->assertNotEquals($germanError, $englishError);
    }
}
