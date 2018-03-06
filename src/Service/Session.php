<?php

namespace SoftExpert\Service;

/**
 * Class Session
 * @package SoftExpert\Service
 * @author Adan Felipe Medeiros <adan.grg@gmail.com>
 */
class Session
{
    public function set($identy, $value)
    {
        $_SESSION[$identy] = $value;
    }

    public function has($identy)
    {
        return isset($_SESSION[$identy]);
    }

    public function get($identy)
    {
        return $_SESSION[$identy];
    }

    public function clear($identy)
    {
        if ($this->has($identy)) {
            unset($_SESSION[$identy]);
        }
    }

    public function addFlash($identy, $data)
    {
        $_SESSION['flash_data'][$identy] = $data;
    }

    public function getFlash($identy)
    {
        $data = $_SESSION['flash_data'][$identy];
        $this->clearFlash($identy);
        return $data;
    }

    public function hasFlash($identy)
    {
        return isset($_SESSION['flash_data'][$identy]);
    }

    private function clearFlash($identy)
    {
        if (isset($_SESSION['flash_data'][$identy])) {
            unset($_SESSION['flash_data'][$identy]);
        }
    }
}
