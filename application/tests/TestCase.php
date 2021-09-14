<?php

namespace Tests;

use Carbon\Carbon;
use Faker\Generator as Faker;
use Laravel\Lumen\Testing\TestCase as BaseTestCase;

abstract class TestCase extends BaseTestCase
{
    protected Faker $faker;

    public function setUp(): void
    {
        parent::setUp();
        $this->faker = app(Faker::class);
    }

    public function createApplication()
    {
        return require __DIR__ . '/../bootstrap/app.php';
    }
    protected function now(): string
    {
        return Carbon::now()->format(DATE_ISO8601);
    }

    protected function tomorrow(): string
    {
        return Carbon::tomorrow()->format(DATE_ISO8601);
    }

    protected function nextMonth(): string
    {
        return Carbon::now()->addMonth()->format(DATE_ISO8601);
    }
}
