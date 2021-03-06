<?php

declare(strict_types=1);

namespace app\traits;


/**
 * Realiza a conexão com o banco de dados
 */
trait Cache
{
    public function cacheWithEtag(string $name, $container, $response)
    {
        $cache = $container->get('cache');
        $response = $cache->withEtag($response, md5($name));
        return $response;
    }
}
