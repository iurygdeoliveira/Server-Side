<?php

/**
 * Function que formata o horário no padrão brasileiro
 * 
 * @param string $date
 * @return string
 */
function date_fmt_br(string $date = "now"): string
{
    return (new DateTime($date))->format(CONF_DATE_BR);
}

/**
 * Function que formata o horário no padrão UNIX
 * 
 * @param string $date
 * @return string
 */
function date_fmt_unix(string $date = "now"): string
{
    return (new DateTime($date))->format(CONF_DATE_APP);
}
