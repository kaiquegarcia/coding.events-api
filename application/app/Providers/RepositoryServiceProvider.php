<?php

namespace App\Providers;

use App\Domain\Repositories\DocumentRepositoryInterface;
use App\Domain\Repositories\EmailRepositoryInterface;
use App\Domain\Repositories\PhoneRepositoryInterface;
use App\Infrastructure\Repositories\Eloquent\DocumentEloquentRepository;
use App\Infrastructure\Repositories\Eloquent\EmailEloquentRepository;
use App\Infrastructure\Repositories\Eloquent\PhoneEloquentRepository;
use Illuminate\Support\ServiceProvider;

class RepositoryServiceProvider extends ServiceProvider
{
    public function register()
    {
        parent::register();

        $this->app->bind(PhoneRepositoryInterface::class, PhoneEloquentRepository::class);
        $this->app->bind(EmailRepositoryInterface::class, EmailEloquentRepository::class);
        $this->app->bind(DocumentRepositoryInterface::class, DocumentEloquentRepository::class);
    }
}
