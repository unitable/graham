<?php

namespace Unitable\Graham\Tests;

use Orchestra\Testbench\TestCase;
use Unitable\Graham\GrahamServiceProvider;

class ExampleTest extends TestCase
{

    protected function getPackageProviders($app)
    {
        return [GrahamServiceProvider::class];
    }
    
    /** @test */
    public function true_is_true()
    {
        $this->assertTrue(true);
    }
}
