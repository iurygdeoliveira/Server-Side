<?php

declare(strict_types=1);
function setFlash($type, $text = '')
{
    $_SESSION['flash'][$type] = $text;
}

function partialGetFlash($type)
{

    $msg = $_SESSION['flash'][$type];
    unset($_SESSION['flash'][$type]);
    return [
        'type' => $type,
        'message' => $msg
    ];
}

function getFlash()
{
    if (isset($_SESSION['flash']['error'])) {
        return partialGetFlash('error');
    }

    if (isset($_SESSION['flash']['success'])) {
        return partialGetFlash('success');
    }

    if (isset($_SESSION['flash']['warning'])) {
        return partialGetFlash('warning');
    }

    if (isset($_SESSION['flash']['info'])) {
        return partialGetFlash('info');
    }

    return '';
}