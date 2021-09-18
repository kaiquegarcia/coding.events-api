<?php

namespace App\Providers;

use App\Domain\Components\Http\ClientInterface;
use App\Domain\Components\Http\ResponseInterface;
use App\Infrastructure\Components\GuzzleHttp\Client;
use App\Infrastructure\Components\GuzzleHttp\Response;
use Illuminate\Support\ServiceProvider;

class ComponentServiceProvider extends ServiceProvider
{
    public function register()
    {
        parent::register();

        $this->app->bind(ClientInterface::class, Client::class);
        $this->app->bind(ResponseInterface::class, Response::class);
    }
}
