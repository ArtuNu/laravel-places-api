<?php

namespace Tests;

use Illuminate\Foundation\Testing\TestCase as BaseTestCase;

abstract class TestCase extends BaseTestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        if ($this->app->runningUnitTests()) {
            // Configura paths temporales para tests
            $this->app['config']->set('view.compiled', env('VIEW_COMPILED_PATH', '/tmp/views'));
            $this->app['config']->set('cache.stores.file.path', env('CACHE_PATH', '/tmp/cache'));
        }
    }
}