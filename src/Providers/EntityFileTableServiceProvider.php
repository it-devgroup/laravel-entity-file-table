<?php

namespace ItDevgroup\LaravelEntityFileTable\Providers;

use ItDevgroup\LaravelEntityFileTable\EntityFileTableService;
use ItDevgroup\LaravelEntityFileTable\EntityFileTableServiceInterface;
use ItDevgroup\LaravelEntityFileTable\Console\Commands\EntityFileTablePublishCommand;
use Illuminate\Support\ServiceProvider;

/**
 * Class EntityFileTableServiceProvider
 * @package ItDevgroup\LaravelEntityFileTable\Providers
 */
class EntityFileTableServiceProvider extends ServiceProvider
{
    /**
     * @return void
     */
    public function boot()
    {
        $this->loadCustomCommands();
        $this->loadCustomConfig();
        $this->loadCustomPublished();
        $this->loadCustomClasses();
        $this->loadCustomLexicon();
    }

    /**
     * @return void
     */
    private function loadCustomCommands()
    {
        if ($this->app->runningInConsole()) {
            $this->commands(
                EntityFileTablePublishCommand::class
            );
        }
    }

    /**
     * @return void
     */
    private function loadCustomConfig()
    {
        $this->mergeConfigFrom(__DIR__ . '/../../config/entity_file_table.php', 'entity_file_table');
    }

    /**
     * @return void
     */
    private function loadCustomPublished()
    {
        if ($this->app->runningInConsole()) {
            $this->publishes(
                [
                    __DIR__ . '/../../config' => base_path('config')
                ],
                'config'
            );
        }
        if ($this->app->runningInConsole()) {
            $this->publishes(
                [
                    __DIR__ . '/../../migration' => database_path('migrations')
                ],
                'migration'
            );
        }
    }

    /**
     * @return void
     */
    private function loadCustomClasses()
    {
        $this->app->singleton(EntityFileTableServiceInterface::class, EntityFileTableService::class);
    }

    /**
     * @return void
     */
    private function loadCustomLexicon()
    {
        $this->loadTranslationsFrom(__DIR__ . '/../../resources/lang', 'entityFileTable');
    }
}
