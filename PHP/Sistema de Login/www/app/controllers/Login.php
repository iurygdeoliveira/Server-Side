<?php

declare(strict_types=1);

namespace app\controllers;

use app\models\User;

class Login extends Base
{
    /**
     * Método que renderiza a tela de login principal
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
                'sistema' => 'teste',
                'title' => 'Login',
                'flash' => getFlash(),
                'link_acesso' => url("access"),
                'link_forgot' => url("recuperar")
            ]
        );
    }

    /**
     * Método que renderiza a tela de recuperação de senha
     *
     * @param mixed $request
     * @param mixed $response
     */
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

    /**
     * Método que valida o acesso do usuário ao sistema
     *
     * @param mixed $request
     * @param mixed $response
     */
    public function access($request, $response)
    {
        //true = campos preenchidos
        //false = campo obrigatório vazio
        // Verificando campos obrigatórios
        $error = required(['email', 'senha']);

        if ($error) {
            setFlash("error", "Campo obrigatório não informado");
            return redirect('/login');
        }

        // Filtrando dados
        $email = filterInput($_POST['email']);
        $senha = filterInput($_POST['senha']);

        //Validando formato de email
        if (!is_email($email)) {
            setFlash("error", "Email informado inválido");
            return redirect('/login');
        }

        // Validando dados
        $user = new User();

        $result = $user->emailExist($email);
        $verifiedPass = password_verify($senha, (empty($result) ? '' : $result->pass));

        if (!$result || !$verifiedPass) {

            setFlash("error", "Login Inválido");
            return redirect('/login');
        } else {
            // FIXME Melhorar lógica do usuário logado
            // Login efetuado
            $_SESSION['user_logged_data'] = [
                'id' => $result->id,
                'name' => $result->name,
                'email' => $result->email
            ];
            return redirect("/admin");
        }
    }

    /**
     * Método que realiza o logout no sistema
     *
     * @param mixed $request
     * @param mixed $response
     */
    public function exit($request, $response)
    {
        unset($_SESSION['user_logged_data']);
        return redirect("/");
    }
}