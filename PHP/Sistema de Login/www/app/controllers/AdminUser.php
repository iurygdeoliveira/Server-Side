<?php

declare(strict_types=1);

namespace app\controllers;

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
            'link_addUser' => url("addUser"), // Rota para adicionar novos usuários
            'link_rmUser' => url("rmUser") // Rota para remover usuários
        ];
    }

    public function user($request, $response)
    {
        $user = new User();

        // Obtendo usuários registrados no banco de dados
        $result = $user->getAll();

        // Ativando classes específicas do CSS para renderizar os estilos na sidebar
        $this->data += [
            'flash' => getFlash(),
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

        // true = campos preenchidos
        // false = campo obrigatório vazio
        // Verificando Campos obrigatórios
        $error = required(['name', 'email', 'pass', 'confirm']);

        if ($error) {
            setFlash("error", "Campo obrigatório não informado");
            return redirect($response, 'user');
        }

        // Filtrando Dados
        $name = filterInput($_POST['name']);
        $email = filterInput($_POST['email']);
        $pass = filterInput($_POST['pass']);
        $confirm = filterInput($_POST['confirm']);

        //Validando formato de email
        if (!is_email($email)) {
            setFlash("error", "Email informado inválido");
            return redirect($response, 'user');
        }

        //Confirmando se pass e confirm são iguais
        if ($pass !== $confirm) {
            setFlash("error", "A senha e a confirmação devem ser iguais");
            return redirect($response, 'user');
        }

        $user = new User();
        $result = $user->emailExist($email);

        if ($result) {
            setFlash("error", "Email já cadastrado");
            return redirect($response, 'user');
        } else {
            // realizar registro do usuario
            $result = $user->insertOne([
                'name' => $name,
                'email' => $email,
                'pass' => passwd($pass)
            ]);

            if (!$result) {
                setFlash("error", $user->getError());
                return redirect($response, 'user');
            } else {
                setFlash("success", "Usuário cadastrado");
                return redirect($response, 'user');
            }
        }
    }

    public function rmUser($request, $response)
    {
        // true = campos preenchidos
        // false = campo obrigatório vazio
        // Verificando Campos obrigatórios
        $error = required(['id', 'email', 'name']);

        if ($error) {
            setFlash("error", "Campo obrigatório não informado");
            return redirect($response, 'user');
        }

        // Filtrando Dados
        $id = filterInput($_POST['id']);
        $email = filterInput($_POST['email']);

        //Validando formato de email
        if (!is_email($email)) {
            setFlash("error", "Email informado inválido");
            return redirect($response, 'user');
        }

        $user = new User();
        $result = $user->deleteByID($id);

        if (!$result) {
            setFlash("error", $user->getError());
            return redirect($response, 'user');
        } else {
            setFlash("success", "Usuário Deletado");
            return redirect($response, 'user');
        }
    }
}