<?php

declare(strict_types=1);

namespace app\controllers;

use Psr\Container\ContainerInterface;
use app\traits\Cache;
use app\traits\Url;
use app\traits\Date;

class Admin extends Base
{

    use Cache, Url, Date;

    // Armazena os dados que serão enviados para a view
    protected $data;
    protected $container;

    public function __construct(ContainerInterface $container)
    {
        // Inicializando os dados default que serão enviados para renderização nas views
        $this->data = [
            'title' => 'Dashboard',
            'sistema' => 'teste',
            'datetime' => $this->date_fmt_br(),
            'year' => date('Y'),
            'link_admin' => $this->url('admin'),
            'link_user' => $this->url('user'),
            'link_home' => $this->url(),
            'link_exit' => $this->url('exit')
        ];

        // Habilitando Cache através do container
        $this->container = $container;
    }

    /**
     * Método responsável pela renderização da view dashboard
     *
     * @param mixed $request
     * @param mixed $response
     */
    public function dashboard($request, $response)
    {
        // Ativando classes específicas do CSS para renderizar os estilos na sidebar
        $this->data += [
            'screen' => 'Dashboard',
            'dashboard' => true,
            'user' => false
        ];

        // Habilitando o cache
        $response = $this->cacheWithEtag(__FUNCTION__, $this->container, $response);

        $nameView = $this->nameView(__CLASS__, __FUNCTION__);
        return $this->getTwig()->render(
            $response,
            $this->setView($nameView),
            $this->data
        );
    }
}