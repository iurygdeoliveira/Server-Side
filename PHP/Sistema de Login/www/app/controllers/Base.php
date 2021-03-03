<?php

declare(strict_types=1);

namespace app\controllers;;

use app\traits\Template;

abstract class Base
{
    use Template;

    /**
     * Método que extrai o nome da classe
     *
     * @param string $class
     * @return string
     */
    protected function nameClass(string $class): string
    {
        $fullName = explode('\\', $class);
        return mb_strtolower(end($fullName));
    }

    /**
     * Método que constroi o nome completo da view que será renderizada como response
     *
     * @param string $class
     * @param string $page
     * @return string
     */
    protected function nameView(string $class, string $page): string
    {
        return $this->nameClass($class) . '/' . $page;
    }
}