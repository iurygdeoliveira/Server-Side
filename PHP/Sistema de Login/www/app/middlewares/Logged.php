<?php

declare(strict_types=1);

namespace app\middlewares;

use Slim\Psr7\Response;


/**
 * Middleware que é executado antes das rotas do admin
 * o usuário apenas executa o admin se tiver realizado login
 */
class Logged
{
    public function __invoke($request, $handler): Response
    {
        $response = $handler->handle($request);
        $existingContent = (string) $response->getBody();

        $response = new Response();
        $response->getBody()->write($existingContent);

        if (!isset($_SESSION['user_logged_data'])) {
            return redirect($response, '/');
        }

        return $response;
    }
}
