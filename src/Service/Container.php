<?php

namespace SoftExpert\Service;

/**
 * Trait Container
 *
 * @package SoftExpert\Service
 *
 * @author Adan Felipe Medeiros<adan.grg@gmail.com>
 * @version 1.0
 */
trait Container
{
    public function offsetExists($offset)
    {
        return $this->container->offsetExists($offset);
    }

    public function offsetGet($offset)
    {
        return $this->container->offsetGet($offset);
    }

    public function offsetSet($offset, $value)
    {
        $this->container->offsetSet($offset, $value);
    }

    public function offsetUnset($offset)
    {
        $this->container->offsetUnset($offset);
    }
}
