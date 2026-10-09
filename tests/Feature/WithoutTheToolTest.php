<?php

namespace Tests\Feature;

use PHPUnit\Framework\Attributes\RunTestsInSeparateProcesses;
use Tests\TestCase;
use ZiffMedia\LaravelCloudflare\Nova\Tools\LaravelCloudflareTool;

/**
 * Runs like an app on Nova 4: the Tool not registered, and the real Tool class never loaded. Each test runs in
 * its own process, so the stand-in from other tests cannot leak in.
 */
#[RunTestsInSeparateProcesses]
class WithoutTheToolTest extends TestCase
{
    public const PURGE_URI = '/nova-vendor/laravel-cloudflare/purge';

    public const PURGE = ['urls' => ['https://example.com/'], 'tags' => ['articles-1']];

    protected bool $usesToolStandIn = false;

    public function test_guests_are_rejected(): void
    {
        $this->postJson(self::PURGE_URI, self::PURGE)->assertUnauthorized();
    }

    public function test_signed_in_users_get_a_404_without_loading_the_tool(): void
    {
        $this->actingAs($this->novaUser())->postJson(self::PURGE_URI, self::PURGE)->assertNotFound();

        $this->assertFalse(class_exists(LaravelCloudflareTool::class, false));
    }
}
