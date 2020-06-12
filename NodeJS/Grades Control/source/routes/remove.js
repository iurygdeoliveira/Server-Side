/*
###########
# IMPORTS #
###########
*/

import express from 'express';
import { promises } from 'fs';
import helper from '../helpers/config.js';

/*
############
# CONSTANT #
############
*/

const { readFile, writeFile } = promises;
const remove = express.Router();

/*
############
# ENDPOINT #
############
*/

remove.delete(helper.endpoints.remove, async (req, res) => {
  let id = Number(req.params.id);

  try {
    // Reading grades.json
    let file = JSON.parse(await readFile(global.gradesFile));

    // Procurando grade informada
    const index = file.grades.findIndex((data) => data.id === id);

    // Caso a grade não seja encontrada retornar erro
    // do contrário realizar atualização na grades
    if (index === -1) {
      res.status(400).send({ error: 'Grade Not Found' });
      return;
    } else {
      const filtered = file.grades.filter((data) => data.id !== id);

      //Atualizando grades
      file.grades = filtered;

      // Escrevendo arquivo no disco, em caso de sucesso
      // enviar novo dado inserido e status 200
      // em caso de erro enviar mensagem de erro e status 400
      await writeFile(global.gradesFile, JSON.stringify(file))
        .then(() => {
          res.send({ OK: 'Arquivo atualizado' });
        })
        .catch((error) => {
          res.status(400).send({ error: error.message });
        });
    }
  } catch (error) {
    // Qualquer erro no decorrer do processo enviar mensagem de erro
    // e status 400
    res.status(400).send({ error: error.message });
  }
});

export default remove;
