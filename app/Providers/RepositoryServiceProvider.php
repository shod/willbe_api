<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

use App\Interfaces\UserRepositoryInterface;
use App\Repositories\UserRepository;

use App\Interfaces\UserInfoRepositoryInterface;
use App\Repositories\UserInfoRepository;

use App\Interfaces\SmsRepositoryInterface;
use App\Repositories\SmsRepository;

class RepositoryServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind(UserRepositoryInterface::class, UserRepository::class);
        $this->app->bind(UserInfoRepositoryInterface::class, UserInfoRepository::class);
        $this->app->bind(SmsRepositoryInterface::class, SmsRepository::class);
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }
}
