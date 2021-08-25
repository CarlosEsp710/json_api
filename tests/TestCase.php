<?php

namespace Tests;

use Illuminate\Foundation\Testing\TestCase as BaseTestCase;
use CloudCreativity\LaravelJsonApi\Testing\MakesJsonApiRequests;

abstract class TestCase extends BaseTestCase
{
    use CreatesApplication, MakesJsonApiRequests;
}
