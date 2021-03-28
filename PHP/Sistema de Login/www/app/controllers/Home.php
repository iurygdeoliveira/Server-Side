<?php

declare(strict_types=1);

namespace app\controllers;

use app\traits\Url;

class Home extends Base
{
    use Url;
    /**
     * Método que renderiza a view home
     *
     * @param mixed $request
     * @param mixed $response
     */
    public function index($request, $response)
    {
        $nameView = $this->nameView(__CLASS__, __FUNCTION__);
        return $this->getTwig()->render(
            $response,
            $this->setView($nameView),
            [
                'title' => 'Home',
                'link_login' => $this->url("login")
            ]

        );
    }

    /**
     * Método que renderiza a view de erro para rotas não encontradas
     *
     * @param mixed $request
     * @param mixed $response
     */
    public function error($request, $response)
    {
        $nameView = $this->nameView(__CLASS__, __FUNCTION__);
        return $this->getTwig()->render(
            $response,
            $this->setView($nameView),
            [
                'title' => 'Erro',
                'link_back' => $this->url_back() // Retornando a pagina anterior
            ]

        );
    }
}