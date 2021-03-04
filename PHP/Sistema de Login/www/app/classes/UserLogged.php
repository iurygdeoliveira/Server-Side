<?php

declare(strict_types=1);

namespace app\classes;

use Slim\Views\Twig;

/**
 * Classe responsável por registrar os usuários logados
 */
class UserLogged
{

    private static $users = [];

    /**
     * Método responsável por setar os usuários logados no sistema
     *
     * @param string $key chave dos usuários
     * @param mixed $value
     * @return void
     */
    public static function set(string $key, mixed $value)
    {
        if (!isset(static::$users[$key])) {
            static::$users[$key] = $value;
        }
    }

    /**
     * Método responsável por dar um aspecto global as variáveis nos templates
     *
     * @param Twig $twig
     * @return void
     */
    public static function load(Twig $twig)
    {
        $env = $twig->getEnvironment();
        foreach (static::$users as $key => $value) {
            $env->addGlobal($key, $value);
        }
    }
}
