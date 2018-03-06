<?php

namespace SoftExpert\Provider;

use Pimple\Container;
use Pimple\ServiceProviderInterface;
use SoftExpert\Service\ViewRenderer;
use SoftExpert\Twig\RouteExtension;
use SoftExpert\Twig\SessionExtension;

/**
 * Class ViewProvider
 *
 * @package SoftExpert\Provider
 * @author Adan Felipe Medeiros <adan.grg@gmail.com>
 * @version 1.0
 */
class TwigProvider implements ServiceProviderInterface
{
    /**
     * @param Container $container
     */
    public function register(Container $container)
    {
        $loader = new \Twig_Loader_Filesystem([
            __DIR__ . '/../Resources/views'
        ]);

        $container['twig.loader'] = $loader;

        $twig = new \Twig_Environment($loader);

        $twig->addExtension(new RouteExtension($container));
        $twig->addExtension(new SessionExtension($container));

        $container['twig'] = $twig;

        $container['view.renderer'] = new ViewRenderer($twig);
    }
}
