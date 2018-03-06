<?php

namespace SoftExpert\Twig;

use Pimple\Container;
use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;

class RouteExtension extends AbstractExtension
{
    /**
     * @var Container
     */
    private $container;

    /**
     * RouteExtension constructor.
     * @param Container $container
     */
    public function __construct(Container $container)
    {
        $this->container = $container;
    }

    public function getFunctions()
    {
        return [
            new TwigFunction('url', [$this, 'generateRoute']),
            new TwigFunction('baseUrl', [$this, 'getBaseUrl']),
        ];
    }

    public function generateRoute($name, $params = [])
    {
        $generator = $this->container['routing.generator'];

        return $generator->generate($name, $params);
    }

    public function getBaseUrl()
    {
        if (isset($_SERVER['HTTPS'])) {
            $protocol = ($_SERVER['HTTPS'] && $_SERVER['HTTPS'] != "off") ? "https" : "http";
        } else {
            $protocol = 'http';
        }

        return $protocol . "://" . $_SERVER['HTTP_HOST'];
    }
}
