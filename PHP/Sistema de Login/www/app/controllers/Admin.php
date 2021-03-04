<?php

declare(strict_types=1);

namespace app\controllers;

class Admin extends Base
{
    // Armazena os dados que serão enviados para a view
    protected $data;
    public function __construct()
    {
        // Inicializando os dados default que serão enviados para renderização nas views
        $this->data = [
            'title' => 'Dashboard',
            'sistema' => 'teste',
            'version' => '0.0.1',
            'datetime' => date_fmt_br(),
            'year' => date('Y'),
            'link_admin' => url('admin'),
            'link_user' => url('user'),
            'link_home' => url(),
            'link_exit' => url('exit')
        ];
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

        $nameView = $this->nameView(__CLASS__, __FUNCTION__);
        return $this->getTwig()->render(
            $response,
            $this->setView($nameView),
            $this->data
        );
    }
}