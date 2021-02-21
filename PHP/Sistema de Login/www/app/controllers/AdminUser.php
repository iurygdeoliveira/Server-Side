<?php

declare(strict_types=1);

namespace app\controllers;

use app\classes\Flash;
use app\models\User;

class AdminUser extends Admin
{

    public function __construct()
    {
        parent::__construct();
        $this->data += [
            'screen' => 'Usuário',
            'dashboard' => false,
            'user' => true,
            'link_addUser' => url("addUser") // Rota para adicionar novos usuários
        ];
    }

    public function user($request, $response)
    {
        $flashes = Flash::getAll();
        $user = new User();

        // Obtendo usuários registrados no banco de dados
        $result = $user->getAll();

        // Ativando classes específicas do CSS para renderizar os estilos na sidebar
        $this->data += [
            'flashes' => $flashes,
            'users' => $result // Usuários registrados no banco

        ];

        $nameView = $this->nameView(__CLASS__, __FUNCTION__);
        return $this->getTwig()->render(
            $response,
            $this->setView($nameView),
            $this->data
        );
    }

    public function addUser($request, $response, $args)
    {
        // Ativando classes específicas do CSS para renderizar os estilos na sidebar
        // $this->data += [

        // ];
        var_dump($_POST);
        // true = campos preenchidos
        // false = campo obrigatório vazio
        $error = required(['name', 'email', 'pass', 'confirm']);

        if ($error) {
            $message = "<script type=\"text/javascript\">toastr.success('Have Fun')</script>";
            Flash::set('backend', $message);
            return redirect($response, 'user');
        }

        $name = filterInput($_POST['name']);
        $email = filterInput($_POST['email']);
        $pass = filterInput($_POST['pass']);
        $confirm = filterInput($_POST['confirm']);

        //Validando formato de email
        if (!is_email($email)) {
            $message = $this->flashMessage('Email informado inválido', 'danger');
            Flash::set('backend', $message);
            return redirect($response, 'user');
        }

        $user = new User();
        $result = $user->emailExist($email);

        if ($result) {
            $message = $this->flashMessage('Email já cadastrado', 'danger');
            Flash::set('backend', $message);
            return redirect($response, 'user');
        } else {
            // realizar registro do email
            $message = "<script type=\"text/javascript\">toastr.success('Have Fun')</script>";
            Flash::set('backend', $message);
            return redirect($response, 'user');
        }
    }
}