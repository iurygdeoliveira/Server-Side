<?php

declare(strict_types=1);

namespace app\traits;

use RedBeanPHP\R;
use RedBeanPHP\RedException;

/**
 * Realiza operação de inserção no banco de dados
 */
trait Create
{
    public function insert(string $table, array $data, int $count)
    {

        try {

            $bean = R::dispense($table, $count);

            foreach ($data as $key => $value) {
                $bean->$key = $value;
            }

            $id = R::store($bean);
            return $id;
        } catch (RedException $e) {
            //FIXME Registrar erros no sistemas log
            $this->error = $e->getMessage();
            return false;
        }
    }
}