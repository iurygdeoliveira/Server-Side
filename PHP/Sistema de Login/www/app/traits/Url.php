<?php

declare(strict_types=1);

namespace app\traits;

trait Url
{
    /**
     * Function que constroi uma url
     *
     * @param string $path
     * @return string
     */
    public function url(string $path = null): string
    {
        if (strpos($_SERVER['HTTP_HOST'], "localhost")) {
            if ($path) {
                return CONF_URL_TEST . "/" . ($path[0] == "/" ? mb_substr($path, 1) : $path);
            }
            return CONF_URL_TEST;
        }

        if ($path) {
            return CONF_URL_BASE . "/" . ($path[0] == "/" ? mb_substr($path, 1) : $path);
        }

        return CONF_URL_BASE;
    }

    /**
     * Function que retornar a ultima url visitada
     *
     * @return string
     */
    public function url_back(): string
    {
        return ($_SERVER['HTTP_REFERER'] ?? url());
    }

    /**
     * Function que redireciona o usuário para a informada
     *
     * @param string $url destino do redirecionamento
     */
    public function redirect(string $url)
    {
        return header("Location: $url");
    }
}