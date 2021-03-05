<?php

/**
 * Comentário:
 * 
 * Arquivo index, realiza a gestão de toda API
 */

declare(strict_types=1);
session_start();
ob_start();

require_once __DIR__ . '/../vendor/autoload.php';

use app\classes\UserLogged;
use Slim\Factory\AppFactory;
use Slim\Middleware\MethodOverrideMiddleware;
use app\controllers\Home;
use app\controllers\Login;
use app\controllers\Admin;
use app\controllers\AdminUser;
use app\middlewares\Logged;

$app = AppFactory::create();

// USER LOGGED
UserLogged::set('user', $_SESSION['user_logged_data'] ?? '');

// WEB ROUTES
$app->get('/', Home::class . ":index");

// LOGIN ROUTES
$app->get('/login', Login::class . ":index");
$app->get('/recuperar', Login::class . ":forgot");
$app->post('/access', Login::class . ":access");
$app->get('/exit', Login::class . ":exit");

// ADMIN ROUTES
// Rotas que só podem ser acessadas se o usuário estiver logado
$app->post('/admin', Admin::class . ":dashboard")->add(new Logged);
$app->get('/admin', Admin::class . ":dashboard")->add(new Logged);

// ADMIN USER ROUTES
$app->get('/user', AdminUser::class . ":user")->add(new Logged);;
$app->post('/user/add', AdminUser::class . ":addUser")->add(new Logged);;
$app->post('/user/delete', AdminUser::class . ":rmUser")->add(new Logged);;
$app->post('/user/update', AdminUser::class . ":updateUser")->add(new Logged);;

// ERROR ROUTES
$app->get('/error', Home::class . ":error");

// Add Override verbs http
$methodOverrideMiddleware = new MethodOverrideMiddleware();
$app->add($methodOverrideMiddleware);
/**
 * Exemplo: 
 * 
 * <form action="<url de destino> method="post" ">
 *      <input type="hidden" name="_METHOD" value="DELETE"/>
 *      <button type="submit" class="btn btn-danger"> Deletar </button>     
 * </form>
 */

// ROUTES NOT FOUND
$app->map(
    ['GET', 'POST', 'DELETE', 'PUT', 'PATCH'],
    '/{routes:.+}',
    function ($resquest, $response) {
        return redirect($response, '/error');
    }
);
$app->run();
ob_end_flush();