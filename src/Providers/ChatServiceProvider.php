<?php

declare(strict_types=1);

namespace SevenSpan\Chat\Providers;

use SevenSpan\Chat\User;
use SevenSpan\Chat\Channel;
use SevenSpan\Chat\Message;
use Illuminate\Support\ServiceProvider;
use SevenSpan\Chat\Exceptions\InvalidConfig;

class ChatServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        if ($this->app->runningInConsole()) {
            $this->publishes([
                __DIR__ . '/../../config/chat.php' => config_path('chat.php'),
            ], 'config');
        }

        $this->loadMigrationsFrom(__DIR__ . '/../../database/migrations');

        $this->app->bind('Channel', function () {
            $this->ensureConfigValuesAreSet();

            return new Channel();
        });

        $this->app->bind('Message', function () {
            $this->ensureConfigValuesAreSet();

            return new Message();
        });

        $this->app->bind('User', function () {
            $this->ensureConfigValuesAreSet();

            return new User();
        });
    }

    public function register(): void
    {
        $this->mergeConfigFrom(__DIR__ . '/../../config/chat.php', 'chat');
    }

    protected function ensureConfigValuesAreSet(): void
    {
        $mandatoryAttributes = config('chat');
        foreach ($mandatoryAttributes as $key => $value) {
            if (empty($value)) {
                throw InvalidConfig::couldNotFindConfig($key);
            }
        }
    }
}
