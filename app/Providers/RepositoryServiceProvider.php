<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

use App\Interfaces\AuthRepositoryInterface;
use App\Repositories\AuthRepository;

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

use App\Interfaces\TestRepositoryInterface;
use App\Repositories\TestRepository;

use App\Interfaces\UserTestRepositoryInterface;
use App\Repositories\UserTestRepository;

use App\Interfaces\FileRepositoryInterface;
use App\Repositories\FileRepository;

use App\Interfaces\UserQuestionAnswerRepositoryInterface;
use App\Repositories\UserQuestionAnswerRepository;

use App\Interfaces\SessionStorageInfoRepositoryInterface;
use App\Repositories\SessionStorageInfoRepository;

use App\Interfaces\MailRepositoryInterface;
use App\Repositories\MailRepository;

use App\Interfaces\PageTextRepositoryInterface;
use App\Repositories\PageTextRepository;

use App\Interfaces\SubscribeRepositoryInterface;
use App\Repositories\SubscribeRepository;

class RepositoryServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind(AuthRepositoryInterface::class, AuthRepository::class);
        $this->app->bind(UserRepositoryInterface::class, UserRepository::class);
        $this->app->bind(UserInfoRepositoryInterface::class, UserInfoRepository::class);
        $this->app->bind(SmsRepositoryInterface::class, SmsRepository::class);
        $this->app->bind(ClientUserRepositoryInterface::class, ClientUserRepository::class);
        $this->app->bind(ProgramRepositoryInterface::class, ProgramRepository::class);
        $this->app->bind(SessionRepositoryInterface::class, SessionRepository::class);
        $this->app->bind(SessionStepRepositoryInterface::class, SessionStepRepository::class);
        $this->app->bind(ConsultationRepositoryInterface::class, ConsultationRepository::class);
        $this->app->bind(TargetRepositoryInterface::class, TargetRepository::class);
        $this->app->bind(TestRepositoryInterface::class, TestRepository::class);
        $this->app->bind(UserTestRepositoryInterface::class, USerTestRepository::class);
        $this->app->bind(FileRepositoryInterface::class, FileRepository::class);
        $this->app->bind(UserQuestionAnswerRepositoryInterface::class, UserQuestionAnswerRepository::class);
        $this->app->bind(SessionStorageInfoRepositoryInterface::class, SessionStorageInfoRepository::class);
        $this->app->bind(MailRepositoryInterface::class, MailRepository::class);
        $this->app->bind(PageTextRepositoryInterface::class, PageTextRepository::class);
        $this->app->bind(SubscribeRepositoryInterface::class, SubscribeRepository::class);
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
