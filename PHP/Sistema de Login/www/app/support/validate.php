<?php

/**
 * Function que verifica se um email é valido
 * 
 * @param string $email
 * @return bool
 */
function is_email(string $email): bool
{
    return filter_var($email, FILTER_VALIDATE_EMAIL);
}

/**
 * Function que verifica se uma senha é valida
 * 
 * @param string $password
 * @return bool
 */
function is_passwd(string $password): bool
{
    if (password_get_info($password)['algo']) {
        return true;
    }

    return (mb_strlen($password) >= CONF_PASSWD_MIN_LEN && mb_strlen($password) <= CONF_PASSWD_MAX_LEN ? true : false);
}

/**
 * Function que verifica os campos obrigatórios
 * 
 * @param array $fields campos a serem verificados
 * @return bool
 */
function required(array $fields): bool
{
    $error = false;
    foreach ($fields as $field) {
        if (empty($_POST[$field])) {
            $error = true;
            break;
        }
    }

    return $error;
}

/**
 * Function que filtra um campo de entrada
 *
 * @param string $field campo a ser filtrado
 */
function filterInput(string $field)
{
    return filter_var($field, FILTER_SANITIZE_STRING);
}