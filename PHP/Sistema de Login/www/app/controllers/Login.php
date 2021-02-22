<?php

declare(strict_types=1);

namespace app\controllers;

use app\classes\Flash;
use app\models\User;

class Login extends Base
{
    public function index($request, $response)
    {

        $nameView = $this->nameView(__CLASS__, __FUNCTION__);
        return $this->getTwig()->render(
            $response,
            $this->setView($nameView),
            [
                'sistema' => 'teste',
                'title' => 'Login',
                'flash' => getFlash(),
                'link_acesso' => url("access"),
                'link_forgot' => url("recuperar")
            ]
        );
    }

    public function forgot($request, $response)
    {

        $nameView = $this->nameView(__CLASS__, __FUNCTION__);
        return $this->getTwig()->render(
            $response,
            $this->setView($nameView),
            [
                'sistema' => 'teste',
                'title' => 'Recuperar senha',
                'link_recovery' => url("renovar"),
                'link_login' => url("login"),
            ]
        );
    }

    public function access($request, $response)
    {
        //true = campos preenchidos
        //false = campo obrigatório vazio
        $error = required(['email', 'senha']);

        if ($error) {
            setFlash("error", "Campo obrigatório não informado");
            return redirect($response, 'login');
        }

        $email = filterInput($_POST['email']);
        $senha = filterInput($_POST['senha']);

        //Validando formato de email
        if (!is_email($email)) {
            setFlash("error", "Email informado inválido");
            return redirect($response, 'login');
        }

        $user = new User();

        $result = $user->emailExist($email);
        $verifiedPass = password_verify($senha, (empty($result) ? '' : $result->pass));

        if (!$result || !$verifiedPass) {

            setFlash("error", "Login Inválido");
            return redirect($response, 'login');
        } else {
            // Login efetuado
            return redirect($response, 'admin');
        }
    }
}
