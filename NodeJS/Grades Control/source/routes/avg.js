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

const { readFile } = promises;
const avg = express.Router();

/*
############
# ENDPOINT #
############
*/

avg.post(helper.endpoints.avg, async (req, res) => {
  let { subject, type } = req.body;

  try {
    // Reading grades.json
    let file = JSON.parse(await readFile(global.gradesFile));

    // Verificando se existe o subject informado
    let findSubject = file.grades.findIndex((data) => data.subject === subject);
    if (findSubject === -1) {
      res.status(400).send({ error: 'Subject Not Found' });
      return;
    }

    // Verificando se existe o type informado
    let findType = file.grades.findIndex((data) => data.type === type);
    if (findType === -1) {
      res.status(400).send({ error: 'Type Not Found' });
      return;
    }

    const dataFiltered = file.grades.filter(
      (grade) => grade.subject === subject && grade.type === type
    );
    if (!dataFiltered || dataFiltered.length === 0) {
      res.status(400).send({ error: 'Subject or Student not found' });
      return;
    }

    const sumValues = dataFiltered.reduce((acc, cur) => acc + cur.value, 0);

    let avg = sumValues / dataFiltered.length;
    let count = dataFiltered.length;

    const result = {
      subject: subject,
      type: type,
      totalValue: sumValues,
      count: count,
      avg: avg,
    };

    res.send(result);

    //console.log(subjectFiltered);
  } catch (error) {
    // Qualquer erro no decorrer do processo enviar mensagem de erro
    // e status 400
    res.status(400).send({ error: error.message });
  }
});

export default avg;
