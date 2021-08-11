<?php

declare(strict_types=1);

namespace app\traits;

trait Flash
{

    /**
     * Function que define a flash message
     *
     * @param string $type tipo de flash message
     * @param string $text texto da flash message
     */
    public function setFlash(string $type, $text = '')
    {
        $_SESSION['flash'][$type] = $text;
    }

    /**
     * Function que constroi a flash message
     *
     * @param [type] $type
     * @return array
     */
    public function buildFlashMessage($type): array
    {
        $msg = $_SESSION['flash'][$type];
        unset($_SESSION['flash'][$type]);
        return [
            'type' => $type,
            'message' => $msg
        ];
    }

    /**
     * Function que obtem a flash message
     */
    public function getFlash()
    {
        if (isset($_SESSION['flash']['error'])) {
            return $this->buildFlashMessage('error');
        }

        if (isset($_SESSION['flash']['success'])) {
            return $this->buildFlashMessage('success');
        }

        if (isset($_SESSION['flash']['warning'])) {
            return $this->buildFlashMessage('warning');
        }

        if (isset($_SESSION['flash']['info'])) {
            return $this->buildFlashMessage('info');
        }

        return '';
    }
}