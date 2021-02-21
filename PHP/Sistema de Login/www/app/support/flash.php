<?php

declare(strict_types=1);
function setFlash($key, $text = '')
{
    $_SESSION['flash'][$key] = $text;
}

function getFlash($key)
{
    if (isset($_SESSION['flash'][$key])) {
        $msg = $_SESSION['flash'][$key];
        unset($_SESSION['flash'][$key]);
        return $msg;
    } else {
        return '';
    }
}