<?php

namespace SoftExpert\Provider;

use Pimple\Container;
use Pimple\ServiceProviderInterface;

/**
 * Class DBProvider
 * @package SoftExpert\Provider
 * @author Adan Felipe Medeiros <adan.grg@gmail.com.>
 */
class DBProvider implements ServiceProviderInterface
{
    public function register(Container $container)
    {
        $dsn = 'pgsql:host='. getenv('DB_HOST') .';port='. getenv('DB_PORT') .';dbname=' . getenv('DB_BASE');
        $container['pdo']  = new \PDO($dsn, getenv('DB_USER'), getenv('DB_PASS'));
    }
}
