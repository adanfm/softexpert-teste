<?php

namespace SoftExpert\Twig;

use Pimple\Container;
use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;

class SessionExtension extends AbstractExtension
{
    /**
     * @var Container
     */
    private $container;

    private $session;

    /**
     * RouteExtension constructor.
     * @param Container $container
     */
    public function __construct(Container $container)
    {
        $this->container = $container;
        $this->session = $container['session'];
    }

    public function getFunctions()
    {
        return [
            new TwigFunction('hasFlash', [$this, 'hasFlash']),
            new TwigFunction('getFlash', [$this, 'getFlash']),
        ];
    }

    public function hasFlash($identy)
    {
        return $this->session->hasFlash($identy);
    }

    public function getFlash($identy)
    {
        return $this->session->getFlash($identy);
    }
}
