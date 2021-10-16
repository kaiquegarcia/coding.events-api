<?php

namespace App\Providers;

use App\Domain\Repositories\AddressRepositoryInterface;
use App\Domain\Repositories\DocumentRepositoryInterface;
use App\Domain\Repositories\EmailRepositoryInterface;
use App\Domain\Repositories\OrganizationRepositoryInterface;
use App\Domain\Repositories\PhoneRepositoryInterface;
use App\Domain\Repositories\UserRepositoryInterface;
use App\Infrastructure\Repositories\Eloquent\AddressEloquentRepository;
use App\Infrastructure\Repositories\Eloquent\DocumentEloquentRepository;
use App\Infrastructure\Repositories\Eloquent\EmailEloquentRepository;
use App\Infrastructure\Repositories\Eloquent\OrganizationEloquentRepository;
use App\Infrastructure\Repositories\Eloquent\PhoneEloquentRepository;
use App\Infrastructure\Repositories\Eloquent\UserEloquentRepository;
use Illuminate\Support\ServiceProvider;

class RepositoryServiceProvider extends ServiceProvider
{
    public function register()
    {
        parent::register();

        $this->app->bind(PhoneRepositoryInterface::class, PhoneEloquentRepository::class);
        $this->app->bind(EmailRepositoryInterface::class, EmailEloquentRepository::class);
        $this->app->bind(DocumentRepositoryInterface::class, DocumentEloquentRepository::class);
        $this->app->bind(AddressRepositoryInterface::class, AddressEloquentRepository::class);
        $this->app->bind(UserRepositoryInterface::class, UserEloquentRepository::class);
        $this->app->bind(OrganizationRepositoryInterface::class, OrganizationEloquentRepository::class);
    }
}
