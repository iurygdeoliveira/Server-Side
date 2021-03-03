<?php

declare(strict_types=1);

namespace app\traits;

use RedBeanPHP\R;
use RedBeanPHP\RedException;

/**
 * Realiza exclusão no banco de dados
 */
trait Delete
{
    public function delete($bean)
    {
        try {

            return R::trash($bean);
        } catch (RedException $e) {
            $this->error = $e->getMessage();
            return false;
        }
    }
}