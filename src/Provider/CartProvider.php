<?php

namespace SoftExpert\Provider;

use Pimple\Container;
use Pimple\ServiceProviderInterface;
use SoftExpert\Service\Cart;

class CartProvider implements ServiceProviderInterface
{
    public function register(Container $container)
    {
        $container['cart'] = new Cart();
    }
}
