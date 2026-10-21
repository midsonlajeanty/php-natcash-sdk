<?php

declare(strict_types=1);

namespace Mds\Natcash\Laravel;

use GuzzleHttp\Client;
use Illuminate\Contracts\Config\Repository;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Support\ServiceProvider;
use Mds\Natcash\Config;
use Mds\Natcash\Core\Constants;
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

            $debug = (bool) ($config['debug'] ?? false);

            $natcash = new Natcash(
                Config::from([
                    'privateKey' => (string) ($config['private_key'] ?? ''),
                    'partnerCode' => (string) ($config['partner_code'] ?? ''),
                    'functionCode' => (string) ($config['function_code'] ?? ''),
                    'username' => (string) ($config['username'] ?? ''),
                    'password' => (string) ($config['password'] ?? ''),
                    'callbackUrl' => (string) ($config['callback_url'] ?? ''),
                    'enableFee' => (bool) ($config['enable_fee'] ?? true),
                    'language' => (string) ($config['language'] ?? 'ht'),
                ]),
                $debug
            );

            $natcash->setClient(new Client([
                'base_uri' => $debug ? Constants::SANDBOX_URL : Constants::LIVE_URL,
                'timeout' => (float) ($config['timeout'] ?? Constants::DEFAULT_TIMEOUT),
                'connect_timeout' => (float) ($config['connect_timeout'] ?? Constants::DEFAULT_CONNECT_TIMEOUT),
            ]));

            return $natcash;
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
