<?php

/**
 * Function que encritpta a senha
 * 
 * @param string $password
 * @return string
 */
function passwd(string $password): string
{
    return password_hash($password, CONF_PASSWD_ALGO, CONF_PASSWD_OPTION);
}

/**
 * Function que verifica se a senha é valida
 * 
 * @param string $password senha informada pelo usuaŕio
 * @param string $hash senha salva no banco de dados para ser comparada
 * @return bool
 */
function passwd_verify(string $password, string $hash): bool
{
    return password_verify($password, $hash);
}