<?php

namespace App\Providers;

use Carton\Container;
use Carton\ServiceProviderInterface;
use Monolog\Handler\ErrorLogHandler;
use Monolog\Logger;

class LoggerProvider implements ServiceProviderInterface
{
    public function register(Container $container): void
    {
       \error_reporting(-1);
       $logger = new Logger(\config("app.name"));
       $logger->pushHandler(new ErrorLogHandler);
       $container->set(Logger::class, $logger);
    }
}