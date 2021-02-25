<?php

declare(strict_types=1);

namespace app\models;

use app\traits\Connection;
use app\traits\Create;
use app\traits\Read;
use app\traits\Update;
use app\traits\Delete;
use RedBeanPHP\R;


class User
{

    // atributtes
    private $error;

    // traits
    use Connection;
    use Create;
    use Read;
    use Update;
    use Delete;

    // methods
    public function __construct()
    {
        $this->connectDB();
    }

    public function getError()
    {
        return $this->error;
    }

    public function emailExist(string $value)
    {
        $result = $this->selectOne('user', 'email = ?', $value);
        // Convertendo $result para o formato de objeto
        return (empty($result) ? false : (object)$result->export());
    }

    public function getAll()
    {
        $result = $this->selectAll('user');
        return (empty($result) ? false : $result);
    }

    public function insertOne(array $data)
    {
        $result = $this->insert('user', $data, 1);
        return (empty($result) ? false : $result);
    }

    public function deleteByID(int $id)
    {
        $bean = $this->selectOne('user', 'id = ?', strval($id));
        if ($bean instanceof R) {
            $result = $this->delete($bean);
            return (empty($result) ? false : $result);
        } else {
            return false;
        }
    }
}
