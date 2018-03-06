<?php

namespace SoftExpert\Service;

use Zend\Diactoros\Response;

class ViewRenderer
{
    /**
     * @var \Twig_Environment
     */
    private $twigEnvironment;

    public function __construct(\Twig_Environment $twigEnvironment)
    {
        $this->twigEnvironment = $twigEnvironment;
    }

    public function render($template, $params = [])
    {
        $result = $this->twigEnvironment->render($template, $params);

        $response = new Response();
        $response->getBody()->write($result);
        return $response;
    }
}
