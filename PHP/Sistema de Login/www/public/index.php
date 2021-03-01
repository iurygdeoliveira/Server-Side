<?php

declare(strict_types=1);
session_start();
ob_start();

require_once __DIR__ . '/../vendor/autoload.php';

use Slim\Factory\AppFactory;
use Slim\Middleware\MethodOverrideMiddleware;
use app\controllers\Home;
use app\controllers\Login;
use app\controllers\Admin;
use app\controllers\AdminUser;

$app = AppFactory::create();

// WEB ROUTES
$app->get('/', Home::class . ":index");

// LOGIN ROUTES
$app->get('/login', Login::class . ":index");
$app->get('/recuperar', Login::class . ":forgot");
$app->post('/access', Login::class . ":access");

// ADMIN ROUTES
$app->post('/admin', Admin::class . ":dashboard");
$app->get('/admin', Admin::class . ":dashboard");
$app->get('/exit', Admin::class . ":exit");

// ADMIN USER ROUTES
$app->get('/user', AdminUser::class . ":user");
$app->post('/user/add', AdminUser::class . ":addUser");
$app->post('/user/delete', AdminUser::class . ":rmUser");
$app->post('/user/update', AdminUser::class . ":updateUser");

// ERROR ROUTES
$app->get('/error', Home::class . ":error");

// Add Override verbs http
$methodOverrideMiddleware = new MethodOverrideMiddleware();
$app->add($methodOverrideMiddleware);

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