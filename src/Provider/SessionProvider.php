<?php

namespace SoftExpert\Provider;

use Pimple\Container;
use Pimple\ServiceProviderInterface;
use SoftExpert\Service\Session;

/**
 * Class SessionProvider
 * @package SoftExpert\Provider
 * @author Adan Felipe Medeiros <adan.grg@gmail.com>
 */
class SessionProvider implements ServiceProviderInterface
{
    public function register(Container $container)
    {
        $container['session'] = new Session();
    }
}
