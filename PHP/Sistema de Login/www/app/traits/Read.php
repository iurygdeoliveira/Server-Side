<?php

declare(strict_types=1);

namespace app\traits;

use RedBeanPHP\R;
use RedBeanPHP\RedException;

/**
 * Realiza operações de busca no banco de dados
 */
trait Read
{
    /**
     * Método que realiza a seleção de um item da entidade no BD
     *
     * @param string $table tabela onde o dado será inserido
     * @param string $params parâmetros de seleção
     * @param string $value valor para configurar o parâmetros de busca
     */
    private function selectOne(string $table, string $params, string $value)
    {

        try {
            return R::findOne($table, $params, [$value]);
        } catch (RedException $e) {
            //FIXME escrever erro em log de sistema
            $this->error = $e->getMessage();
            return false;
        }
    }

    /**
     * Método que busca todos os dados de uma tabela
     *
     * @param string $Nome da Tabela
     */
    private function selectAll(string $table)
    {

        try {
            return R::findAndExport($table);
        } catch (RedException $e) {
            //FIXME escrever erro em log de sistema
            $this->error = $e->getMessage();
            return false;
        }
    }
}