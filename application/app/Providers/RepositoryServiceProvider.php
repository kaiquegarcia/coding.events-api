<?php

namespace App\Providers;

use App\Domain\Repositories\PhoneRepositoryInterface;
use App\Infrastructure\Repositories\Eloquent\PhoneEloquentRepository;
use Illuminate\Support\ServiceProvider;

class RepositoryServiceProvider extends ServiceProvider
{
    public function register()
    {
        parent::register();

        $this->app->bind(PhoneRepositoryInterface::class, PhoneEloquentRepository::class);
    }
}
