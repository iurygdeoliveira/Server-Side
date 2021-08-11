<?php

/**
 * Comentário:
 * 
 * Arquivo index, realiza a gestão de requests de toda API
 */

declare(strict_types=1);
session_start();
ob_start();

// Autoload do Composer
require_once __DIR__ . '/../vendor/autoload.php';

use DI\Container;
use app\controllers\Home;
use app\controllers\Login;
use app\controllers\Admin;
use app\controllers\AdminUser;
use app\classes\UserLogged;
use app\traits\Url;
use app\middlewares\Logged;
use Slim\Factory\AppFactory;
use Slim\Middleware\MethodOverrideMiddleware;
use Slim\Csrf\Guard;
use Slim\HttpCache\Cache;
use Slim\HttpCache\CacheProvider;
use Psr\Http\Server\RequestHandlerInterface;
use Psr\Http\Message\ServerRequestInterface;
use Monolog\Logger;
use Monolog\Handler\StreamHandler;

// Criando sistema de log do sistema
// FIXME Armazenando no MongoDB
// $log = new Logger(CONF_SITE_NAME);
// $log->pushHandler(new StreamHandler('logs/app.log'));

// Criando container de injeção de dependências
$container = new Container();
AppFactory::setContainer($container);

// Criando App
$app = AppFactory::create();

// Habilitando Http Caching com Container
$container->set('cache', function () {
    return new CacheProvider();
});

// Register the http cache middleware.
$app->add(new Cache());

//Habilitando Proteção contra CSRF
$responseFactory = $app->getResponseFactory();
$container->set('csrf', function () use ($responseFactory) {
    $guard = new Guard($responseFactory);
    $guard->setFailureHandler(function (ServerRequestInterface $request, RequestHandlerInterface $handler) {
        $request = $request->withAttribute("csrf_status", false);
        return $handler->handle($request);
    });
    return $guard;
});

// USER LOGGED
UserLogged::set('user', $_SESSION['user_logged_data'] ?? '');

// WEB ROUTES
$app->get('/', Home::class . ":index");

// LOGIN ROUTES
$app->get('/login', Login::class . ":index")->add('csrf');
$app->get('/recuperar', Login::class . ":forgot");
$app->post('/access', Login::class . ":access")->add('csrf');
$app->get('/exit', Login::class . ":exit");

// ADMIN ROUTES
// Só podem ser acessadas se o usuário estiver logado
$app->post('/admin', Admin::class . ":dashboard")->add(new Logged);
$app->get('/admin', Admin::class . ":dashboard")->add(new Logged);

// ADMIN USER ROUTES
// Só podem ser acessadas se o usuário estiver logado
$app->get('/user', AdminUser::class . ":user")->add(new Logged)->add('csrf');
$app->post('/user/add', AdminUser::class . ":addUser")->add(new Logged)->add('csrf');
$app->post('/user/delete', AdminUser::class . ":rmUser")->add(new Logged)->add('csrf');
$app->post('/user/update', AdminUser::class . ":updateUser")->add(new Logged)->add('csrf');

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
        return Url::redirect($response, '/error');
    }
);
$app->run();
ob_end_flush();
