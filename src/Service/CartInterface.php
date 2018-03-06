<?php

namespace SoftExpert\Service;

/**
 * Interface CartInterface
 *
 * @package SoftExpert\Service
 * @author Adan Felipe Medeiros <adan.grg@gmail.com>
 * @version 1.0
 */
interface CartInterface
{
    /**
     * @param array $item
     * @return boolean
     */
    public function addItem(array $item);

    /**
     * @param $id
     * @return array|boolean
     */
    public function getItem($id);

    /**
     * @param $id
     * @return bool
     */
    public function removeItem($id);

    /**
     * @return array
     */
    public function getAll();
}
