<?php

declare(strict_types=1);

namespace app\controllers;

use app\models\User;
use app\traits\Csrf;
use app\traits\Flash;
use app\traits\Url;
use app\traits\Validate;
use Psr\Container\ContainerInterface;

class Login extends Base
{
    protected $container;

    use Csrf, Url, Flash, Validate;

    public function __construct(ContainerInterface $container)
    {

        $this->container = $container;
    }

    /**
     * Método que renderiza a tela de login principal
     *
     * @param mixed $request
     * @param mixed $response
     */
    public function index($request, $response)
    {
        // Habilitando o CSRF
        $csrf = Csrf::getCsrf($request, $this->container->get('csrf'));

        $nameView = $this->nameView(__CLASS__, __FUNCTION__);
        return $this->getTwig()->render(
            $response,
            $this->setView($nameView),
            [
                'sistema' => 'teste',
                'title' => 'Login',
                'flash' => $this->getFlash(),
                'link_acesso' => $this->url("access"),
                'link_forgot' => $this->url("recuperar"),
                'csrf' => $csrf
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
                'link_recovery' => $this->url("renovar"),
                'link_login' => $this->url("login"),
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

        // Verificando Código CSRF
        $csrf = Csrf::validateCsrf($request);
        if (!$csrf) {
            $this->setFlash("error", "Csrf inválido");
            return $this->redirect('/login');
        }

        //true = campos preenchidos
        //false = campo obrigatório vazio
        // Verificando campos obrigatórios
        $error = $this->required(['email', 'senha']);

        if ($error) {
            $this->setFlash("error", "Campo obrigatório não informado");
            return $this->redirect('/login');
        }

        // Filtrando dados
        $email = $this->filterInput($_POST['email']);
        $senha = $this->filterInput($_POST['senha']);

        //Validando formato de email
        if (!$this->is_email($email)) {
            $this->setFlash("error", "Email informado inválido");
            return $this->redirect('/login');
        }

        // Validando dados
        $user = new User();

        $result = $user->emailExist($email);
        $verifiedPass = password_verify($senha, (empty($result) ? '' : $result->pass));

        if (!$result || !$verifiedPass) {

            $this->setFlash("error", "Login Inválido");
            return $this->redirect('/login');
        } else {
            // FIXME Melhorar lógica do usuário logado
            // Login efetuado
            $_SESSION['user_logged_data'] = [
                'id' => $result->id,
                'name' => $result->name,
                'email' => $result->email
            ];
            return $this->redirect("/admin");
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
        return $this->redirect("/");
    }
}