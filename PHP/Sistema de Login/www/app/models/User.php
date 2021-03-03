<?php

declare(strict_types=1);

namespace app\models;

use app\traits\Connection;
use app\traits\Create;
use app\traits\Read;
use app\traits\Update;
use app\traits\Delete;

class User
{

    private $error;

    // traits
    use Connection; // Realiza a conexão com o BD
    use Create; // Realiza as tarefas de criação de dados no BD
    use Read; // Realiza as tarefas de leitura de dados no BD
    use Update; // Realiza as tarefas de update de dados no BD
    use Delete; // Realiza as tarefas de deleção de dados no BD


    public function __construct()
    {   // Conecta no BD
        $this->connectDB();
    }

    /**
     * Método que retorna os erros
     */
    public function getError()
    {
        return $this->error;
    }

    /**
     * Método que verifica a um email existe
     *
     * @param string $value email a ser verificado
     */
    public function emailExist(string $value)
    {
        $result = $this->selectOne('user', 'email = ?', $value);

        // Convertendo $result para o formato de objeto
        return (empty($result) ? false : (object)$result->export());
    }

    /**
     * Método que busca todos os dados em uma tabela específica
     *
     * @param string $table nome da tabela onde os dados devem ser obtidos
     */
    public function getAll(string $table)
    {
        $result = $this->selectAll($table);
        return (empty($result) ? false : $result);
    }

    /**
     * Método que Insere um usuário na tabela user
     *
     * @param array $data dados do usuário para inserção
     */
    public function insertOne(array $data)
    {
        $result = $this->insert('user', $data, 1);
        return (empty($result) ? false : $result);
    }

    /**
     * Método que deleta um usuário pelo id
     *
     * @param string $id
     */
    public function deleteByID(string $id)
    {
        $bean = $this->selectOne('user', 'id = ?', $id);
        if (!empty($bean)) {
            $result = $this->delete($bean);
            return (empty($result) ? false : $result);
        } else {
            return false;
        }
    }

    /**
     * Método que atualiza um usuário
     *
     * @param array $data
     */
    public function updateOne(array $data)
    {
        // Destructing

        $bean = $this->selectOne('user', 'id = ?', $data['id']);

        // Caso o usuário informe uma senha em branco
        // a senha não será atualizada no BD
        if (empty($data['pass'])) {
            unset($data['pass']);
        } else {
            $data['pass'] = passwd($data['pass']);
        }
        return $this->update($bean, $data);
    }
}