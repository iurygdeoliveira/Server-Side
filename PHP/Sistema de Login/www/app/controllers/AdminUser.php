<?php

declare(strict_types=1);

namespace app\controllers;

use app\models\User;
use app\traits\Csrf;
use Psr\Container\ContainerInterface;

class AdminUser extends Admin
{

    use Csrf;

    public function __construct(ContainerInterface $container)
    {
        // Inicializando os dados default que serão enviados para renderização nas views
        parent::__construct($container);
        $this->data += [
            'screen' => 'Usuário',
            'dashboard' => false, // Desativar js especificos do dashboard
            'user' => true, // Ativar js especificos para user
            'link_addUser' => url("user/add"), // Rota para adicionar novos usuários
            'link_rmUser' => url("user/delete"), // Rota para remover usuários
            'link_updateUser' => url("user/update") // Rota para atualizar usuários
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
            'url' => url(),
            'flash' => getFlash(), // Obtendo flash messages se houver
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
            setFlash("error", "Csrf inválido");
            return redirect('/user');
        }

        // true = campos preenchidos
        // false = campo obrigatório vazio
        // Verificando Campos obrigatórios
        $error = required(['name', 'email', 'pass', 'confirm']);

        if ($error) {
            setFlash("error", "Campo obrigatório não informado");
            return redirect('/user');
        }

        // Filtrando Dados
        $name = filterInput($_POST['name']);
        $email = filterInput($_POST['email']);
        $pass = filterInput($_POST['pass']);
        $confirm = filterInput($_POST['confirm']);

        //Validando formato de email
        if (!is_email($email)) {
            setFlash("error", "Email informado inválido");
            return redirect('/user');
        }

        //Confirmando se pass e confirm são iguais
        if ($pass !== $confirm) {
            setFlash("error", "A senha e a confirmação devem ser iguais");
            return redirect('/user');
        }

        // Inserindo usuario no Banco de Dados
        $user = new User();

        $data = [
            'name' => $name,
            'email' => $email,
            'pass' => passwd($pass)
        ];

        $result = $user->insertUser($data);

        if ($result) {
            setFlash("success", "Usuário cadastrado");
            return redirect('/user');
        } else {
            setFlash("error", $user->getError());
            return redirect('/user');
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
            setFlash("error", "Csrf inválido");
            return redirect('/user');
        }

        // true = campos preenchidos
        // false = campo obrigatório vazio
        // Verificando Campos obrigatórios
        $error = required(['id', 'email', 'name']);

        if ($error) {
            setFlash("error", "Campo obrigatório não informado");
            return redirect('/user');
        }

        // Filtrando Dados
        $id = filterInput($_POST['id']);
        $email = filterInput($_POST['email']);

        //Validando formato de email
        if (!is_email($email)) {
            setFlash("error", "Email informado inválido");
            return redirect('/user');
        }

        // Removendo usuários
        $user = new User();
        $result = $user->deleteByID($id);

        if (!$result) {
            setFlash("error", $user->getError());
            return redirect('/user');
        } else {
            setFlash("success", "Usuário Deletado");
            return redirect('/user');
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
            setFlash("error", "Csrf inválido");
            return redirect('/user');
        }

        // true = campos preenchidos
        // false = campo obrigatório vazio
        // Verificando Campos obrigatórios
        $error = required(['id', 'email', 'name']);

        if ($error) {
            setFlash("error", "Campo obrigatório não informado");
            return redirect('/user');
        }

        // Filtrando Dados
        $id = filterInput($_POST['id']);
        $email = filterInput($_POST['email']);
        $name = filterInput($_POST['name']);
        $pass = filterInput($_POST['pass']);

        //Validando formato de email
        if (!is_email($email)) {
            setFlash("error", "Email informado inválido");
            return redirect('/user');
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
            setFlash("success", "Usuário atualizado");
            return redirect('/user');
        } else {
            // realizar registro do usuario
            setFlash("error", "Usuário não atualizado");
            return redirect('/user');
        }
    }
}
