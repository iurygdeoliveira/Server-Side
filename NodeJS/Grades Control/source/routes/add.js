/*
###########
# IMPORTS #
###########
*/

import express from 'express';
import moment from 'moment';
import { promises } from 'fs';
import helper from '../helpers/config.js';

/*
############
# CONSTANT #
############
*/

const { writeFile, readFile } = promises;
const add = express.Router();

/*
############
# ENDPOINT #
############
*/

add.post(helper.endpoints.add, async (req, res) => {
  let data = req.body;

  try {
    // Lendo arquivo grades.json
    let file = JSON.parse(await readFile(global.gradesFile));

    // Montando novo dado
    data = {
      id: file.nextId++,
      student: data.student,
      subject: data.subject,
      type: data.type,
      value: data.value,
      timestamp: moment().format(),
    };

    // Inserindo novo dado na grade
    file.grades.push(data);

    // Escrevendo arquivo no disco, em caso de sucesso
    // enviar novo dado inserido e status 200
    // em caso de erro enviar mensagem de erro e status 400
    await writeFile(global.gradesFile, JSON.stringify(file))
      .then(() => {
        res.send(data);
      })
      .catch((error) => {
        res.status(400).send({ error: error.message });
      });
  } catch (error) {
    // Qualquer erro no decorrer do processo enviar mensagem de erro
    // e status 400
    res.status(400).send({ error: error.message });
  }
});

/*
 ###################
 # EXPORTING ROUTE #
 ###################
 */

export default add;
