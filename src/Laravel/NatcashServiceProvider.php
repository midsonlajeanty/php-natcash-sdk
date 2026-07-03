<?php

declare(strict_types=1);

namespace Mds\Natcash\Laravel;

use Illuminate\Contracts\Config\Repository;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Support\ServiceProvider;
use Mds\Natcash\Config;
use Mds\Natcash\Natcash;
use Mds\Natcash\NatcashInterface;

final class NatcashServiceProvider extends ServiceProvider
{
    /**
     * Register the NatCash gateway in the container.
     */
    public function register(): void
    {
        $this->mergeConfigFrom(__DIR__.'/../../config/natcash.php', 'natcash');

        $this->app->singleton(NatcashInterface::class, static function (Application $app): Natcash {
            /** @var Repository $repository */
            $repository = $app->make('config');

            /** @var array<string, mixed> $config */
            $config = $repository->get('natcash', []);

            return new Natcash(
                new Config(
                    (string) ($config['private_key'] ?? ''),
                    (string) ($config['partner_code'] ?? ''),
                    (string) ($config['function_code'] ?? ''),
                    (string) ($config['username'] ?? ''),
                    (string) ($config['password'] ?? ''),
                    (string) ($config['callback_url'] ?? ''),
                    (bool) ($config['enable_fee'] ?? true),
                    (string) ($config['language'] ?? 'ht'),
                ),
                (bool) ($config['debug'] ?? false)
            );
        });

        $this->app->alias(NatcashInterface::class, 'natcash');
    }

    /**
     * Publish the configuration file when running in the console.
     */
    public function boot(): void
    {
        if ($this->app->runningInConsole()) {
            $this->publishes([
                __DIR__.'/../../config/natcash.php' => $this->app->basePath('config/natcash.php'),
            ], 'natcash-config');
        }
    }
}
