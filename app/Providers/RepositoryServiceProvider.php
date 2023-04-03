<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

use App\Interfaces\UserRepositoryInterface;
use App\Repositories\UserRepository;

use App\Interfaces\UserInfoRepositoryInterface;
use App\Repositories\UserInfoRepository;

use App\Interfaces\SmsRepositoryInterface;
use App\Repositories\SmsRepository;

use App\Interfaces\ClientUserRepositoryInterface;
use App\Repositories\ClientUserRepository;

use App\Interfaces\ProgramRepositoryInterface;
use App\Repositories\ProgramRepository;

use App\Interfaces\SessionRepositoryInterface;
use App\Repositories\SessionRepository;

use App\Interfaces\SessionStepRepositoryInterface;
use App\Repositories\SessionStepRepository;

use App\Interfaces\ConsultationRepositoryInterface;
use App\Repositories\ConsultationRepository;

use App\Interfaces\TargetRepositoryInterface;
use App\Repositories\TargetRepository;

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
        $this->app->bind(ClientUserRepositoryInterface::class, ClientUserRepository::class);
        $this->app->bind(ProgramRepositoryInterface::class, ProgramRepository::class);
        $this->app->bind(SessionRepositoryInterface::class, SessionRepository::class);
        $this->app->bind(SessionStepRepositoryInterface::class, SessionStepRepository::class);
        $this->app->bind(ConsultationRepositoryInterface::class, ConsultationRepository::class);
        $this->app->bind(TargetRepositoryInterface::class, TargetRepository::class);
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
