<?php

declare(strict_types=1);

namespace app\traits;

use Slim\Csrf\Guard;
use Slim\Psr7\Factory\ResponseFactory;

/**
 * Gera os dados de CSRF
 */
trait Csrf
{
    /**
     * Gera o código CSRF
     *
     * @param [type] $request requisição feita pelo usuário
     * @param [type] $csrf middleware csrf
     * @return void
     */
    public static function getCsrf($request, $csrf)
    {

        $nameKey = $csrf->getTokenNameKey();
        $valueKey = $csrf->getTokenValueKey();
        $name = $request->getAttribute($nameKey);
        $value = $request->getAttribute($valueKey);

        return [
            'nameKey' => $nameKey,
            'valueKey' => $valueKey,
            'name' => $name,
            'value' => $value,
        ];
    }

    public static function validateCsrf($request)
    {
        if (false === $request->getAttribute('csrf_status')) {
            return false;
        } else {
            return true;
        }
    }
}
