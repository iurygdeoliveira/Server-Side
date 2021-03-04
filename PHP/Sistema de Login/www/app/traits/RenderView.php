<?php

declare(strict_types=1);

namespace app\traits;

use app\classes\UserLogged;
use Slim\Views\Twig;
use Exception;

/**
 * Trait com métodos utilizados na renderização das views
 */
trait RenderView
{
    public function getTwig()
    {
        try {
            $twig = Twig::create(CONF_DIR_VIEWS);
            UserLogged::load($twig); // Registrando usuários logados
            return $twig;
        } catch (Exception $e) {
            var_dump($e->getMessage());
        }
    }

    public function setView(string $name)
    {
        return $name . CONF_EXT_VIEWS;
    }
}
