<?php

declare(strict_types=1);

namespace app\controllers;

use app\models\User;
use app\traits\Csrf;
use app\traits\Flash;
use app\traits\Password;
use app\traits\Validate;
use Psr\Container\ContainerInterface;

class AdminUser extends Admin
{

    use Csrf, Flash, Validate, Password;

    public function __construct(ContainerInterface $container)
    {
        // Inicializando os dados default que serão enviados para renderização nas views
        parent::__construct($container);
        $this->data += [
            'screen' => 'Usuário',
            'dashboard' => false, // Desativar js especificos do dashboard
            'user' => true, // Ativar js especificos para user
            'link_addUser' => $this->url("user/add"), // Rota para adicionar novos usuários
            'link_rmUser' => $this->url("user/delete"), // Rota para remover usuários
            'link_updateUser' => $this->url("user/update") // Rota para atualizar usuários
        ];
    }

    /**
     * Método que renderiza a view de usuários
     *
     * @param mixed $request
     * @param mixed $response
     */
    public function user($request, $response)
    {
        // Habilitando o cache
        $response = $this->cacheWithEtag(__FUNCTION__, $this->container, $response);

        // Habilitando o CSRF
        $csrf = Csrf::getCsrf($request, $this->container->get('csrf'));

        $user = new User();

        // Obtendo usuários registrados no banco de dados
        $result = $user->getAll('user');

        $this->data += [
            'url' => $this->url(),
            'flash' => $this->getFlash(), // Obtendo flash messages se houver
            'users' => $result, // Usuários registrados no banco
            'csrf' => $csrf
        ];

        // Renderizando view para ser exibida ao usuário
        $nameView = $this->nameView(__CLASS__, __FUNCTION__);
        return $this->getTwig()->render(
            $response,
            $this->setView($nameView),
            $this->data
        );
    }

    /**
     * Método que realiza a adição de usuários no sistema
     *
     * @param mixed $request
     * @param mixed $response
     */
    public function addUser($request, $response)
    {

        // Verificando Código CSRF
        $csrf = Csrf::validateCsrf($request);
        if (!$csrf) {
            $this->setFlash("error", "Csrf inválido");
            return $this->redirect('/user');
        }

        // true = campos preenchidos
        // false = campo obrigatório vazio
        // Verificando Campos obrigatórios
        $error = $this->required(['name', 'email', 'pass', 'confirm']);

        if ($error) {
            $this->setFlash("error", "Campo obrigatório não informado");
            return $this->redirect('/user');
        }

        // Filtrando Dados
        $name = $this->filterInput($_POST['name']);
        $email = $this->filterInput($_POST['email']);
        $pass = $this->filterInput($_POST['pass']);
        $confirm = $this->filterInput($_POST['confirm']);

        //Validando formato de email
        if (!$this->$this->is_email($email)) {
            $this->setFlash("error", "Email informado inválido");
            return $this->redirect('/user');
        }

        //Confirmando se pass e confirm são iguais
        if ($pass !== $confirm) {
            $this->setFlash("error", "A senha e a confirmação devem ser iguais");
            return $this->redirect('/user');
        }

        // Inserindo usuario no Banco de Dados
        $user = new User();

        $data = [
            'name' => $name,
            'email' => $email,
            'pass' => $this->passwd($pass)
        ];

        $result = $user->insertUser($data);

        if ($result) {
            $this->setFlash("success", "Usuário cadastrado");
            return $this->redirect('/user');
        } else {
            $this->setFlash("error", $user->getError());
            return $this->redirect('/user');
        }
    }

    /**
     * Método que realizar a remoção de usuários do banco
     *
     * @param mixed $request
     * @param mixed $response
     */
    public function rmUser($request, $response)
    {

        // Verificando Código CSRF
        $csrf = Csrf::validateCsrf($request);
        if (!$csrf) {
            $this->setFlash("error", "Csrf inválido");
            return $this->redirect('/user');
        }

        // true = campos preenchidos
        // false = campo obrigatório vazio
        // Verificando Campos obrigatórios
        $error = $this->required(['id', 'email', 'name']);

        if ($error) {
            $this->setFlash("error", "Campo obrigatório não informado");
            return $this->redirect('/user');
        }

        // Filtrando Dados
        $id = $this->filterInput($_POST['id']);
        $email = $this->filterInput($_POST['email']);

        //Validando formato de email
        if (!$this->is_email($email)) {
            $this->setFlash("error", "Email informado inválido");
            return $this->redirect('/user');
        }

        // Removendo usuários
        $user = new User();
        $result = $user->deleteByID($id);

        if (!$result) {
            $this->setFlash("error", $user->getError());
            return $this->redirect('/user');
        } else {
            $this->setFlash("success", "Usuário Deletado");
            return $this->redirect('/user');
        }
    }

    /**
     * Método que realiza atualizações no usuário
     *
     * @param mixed $request
     * @param mixed $response
     */
    public function updateUser($request, $response)
    {

        // Verificando Código CSRF
        $csrf = Csrf::validateCsrf($request);
        if (!$csrf) {
            $this->setFlash("error", "Csrf inválido");
            return $this->redirect('/user');
        }

        // true = campos preenchidos
        // false = campo obrigatório vazio
        // Verificando Campos obrigatórios
        $error = $this->required(['id', 'email', 'name']);

        if ($error) {
            $this->setFlash("error", "Campo obrigatório não informado");
            return $this->redirect('/user');
        }

        // Filtrando Dados
        $id = $this->filterInput($_POST['id']);
        $email = $this->filterInput($_POST['email']);
        $name = $this->filterInput($_POST['name']);
        $pass = $this->filterInput($_POST['pass']);

        //Validando formato de email
        if (!$this->is_email($email)) {
            $this->setFlash("error", "Email informado inválido");
            return $this->redirect('/user');
        }

        // Atualizando usuário
        $user = new User();

        $data = [
            'id' => $id,
            'name' => $name,
            'email' => $email,
            'pass' => $pass
        ];

        $result = $user->updateOne($data);

        if ($result) {
            $this->setFlash("success", "Usuário atualizado");
            return $this->redirect('/user');
        } else {
            // realizar registro do usuario
            $this->setFlash("error", "Usuário não atualizado");
            return $this->redirect('/user');
        }
    }
}