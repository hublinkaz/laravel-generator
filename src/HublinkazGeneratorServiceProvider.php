<?php

namespace Hublinkaz\Generator;

use Illuminate\Support\ServiceProvider;
use Hublinkaz\Generator\Commands\API\APIControllerGeneratorCommand;
use Hublinkaz\Generator\Commands\API\APIGeneratorCommand;
use Hublinkaz\Generator\Commands\API\APIRequestsGeneratorCommand;
use Hublinkaz\Generator\Commands\API\TestsGeneratorCommand;
use Hublinkaz\Generator\Commands\APIScaffoldGeneratorCommand;
use Hublinkaz\Generator\Commands\Common\MigrationGeneratorCommand;
use Hublinkaz\Generator\Commands\Common\ModelGeneratorCommand;
use Hublinkaz\Generator\Commands\Common\RepositoryGeneratorCommand;
use Hublinkaz\Generator\Commands\Publish\GeneratorPublishCommand;
use Hublinkaz\Generator\Commands\Publish\LayoutPublishCommand;
use Hublinkaz\Generator\Commands\Publish\PublishTemplateCommand;
use Hublinkaz\Generator\Commands\Publish\PublishUserCommand;
use Hublinkaz\Generator\Commands\RollbackGeneratorCommand;
use Hublinkaz\Generator\Commands\Scaffold\ControllerGeneratorCommand;
use Hublinkaz\Generator\Commands\Scaffold\RequestsGeneratorCommand;
use Hublinkaz\Generator\Commands\Scaffold\ScaffoldGeneratorCommand;
use Hublinkaz\Generator\Commands\Scaffold\ViewsGeneratorCommand;

class HublinkazGeneratorServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {
        if ($this->app->runningInConsole()) {
            $configPath = __DIR__.'/../config/laravel_generator.php';
            $this->publishes([
                $configPath => config_path('hublinkaz/laravel_generator.php'),
            ]);
        }
    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
        $this->mergeConfigFrom(__DIR__.'/../config/laravel_generator.php', 'hublinkaz.laravel_generator');

        $this->app->singleton('hublinkaz.publish', function ($app) {
            return new GeneratorPublishCommand();
        });

        $this->app->singleton('hublinkaz.api', function ($app) {
            return new APIGeneratorCommand();
        });

        $this->app->singleton('hublinkaz.scaffold', function ($app) {
            return new ScaffoldGeneratorCommand();
        });

        $this->app->singleton('hublinkaz.publish.layout', function ($app) {
            return new LayoutPublishCommand();
        });

        $this->app->singleton('hublinkaz.publish.templates', function ($app) {
            return new PublishTemplateCommand();
        });

        $this->app->singleton('hublinkaz.api_scaffold', function ($app) {
            return new APIScaffoldGeneratorCommand();
        });

        $this->app->singleton('hublinkaz.migration', function ($app) {
            return new MigrationGeneratorCommand();
        });

        $this->app->singleton('hublinkaz.model', function ($app) {
            return new ModelGeneratorCommand();
        });

        $this->app->singleton('hublinkaz.repository', function ($app) {
            return new RepositoryGeneratorCommand();
        });

        $this->app->singleton('hublinkaz.api.controller', function ($app) {
            return new APIControllerGeneratorCommand();
        });

        $this->app->singleton('hublinkaz.api.requests', function ($app) {
            return new APIRequestsGeneratorCommand();
        });

        $this->app->singleton('hublinkaz.api.tests', function ($app) {
            return new TestsGeneratorCommand();
        });

        $this->app->singleton('hublinkaz.scaffold.controller', function ($app) {
            return new ControllerGeneratorCommand();
        });

        $this->app->singleton('hublinkaz.scaffold.requests', function ($app) {
            return new RequestsGeneratorCommand();
        });

        $this->app->singleton('hublinkaz.scaffold.views', function ($app) {
            return new ViewsGeneratorCommand();
        });

        $this->app->singleton('hublinkaz.rollback', function ($app) {
            return new RollbackGeneratorCommand();
        });

        $this->app->singleton('hublinkaz.publish.user', function ($app) {
            return new PublishUserCommand();
        });

        $this->commands([
            'hublinkaz.publish',
            'hublinkaz.api',
            'hublinkaz.scaffold',
            'hublinkaz.api_scaffold',
            'hublinkaz.publish.layout',
            'hublinkaz.publish.templates',
            'hublinkaz.migration',
            'hublinkaz.model',
            'hublinkaz.repository',
            'hublinkaz.api.controller',
            'hublinkaz.api.requests',
            'hublinkaz.api.tests',
            'hublinkaz.scaffold.controller',
            'hublinkaz.scaffold.requests',
            'hublinkaz.scaffold.views',
            'hublinkaz.rollback',
            'hublinkaz.publish.user',
        ]);
    }
}
