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
const sum = express.Router();

/*
############
# ENDPOINT #
############
*/

sum.post(helper.endpoints.sum, async (req, res) => {
  let { student, subject } = req.body;

  try {
    // Reading grades.json
    let file = JSON.parse(await readFile(global.gradesFile));

    // Verificando se existe o student informado
    let findStudent = file.grades.findIndex((data) => data.student === student);
    if (findStudent === -1) {
      res.status(400).send({ error: 'Student Not Found' });
      return;
    }

    // Verificando se existe o subject informado
    let findSubject = file.grades.findIndex((data) => data.subject === subject);
    if (findSubject === -1) {
      res.status(400).send({ error: 'Subject Not Found' });
      return;
    }

    const dataFiltered = file.grades.filter(
      (grade) => grade.student === student && grade.subject === subject
    );
    if (!dataFiltered || dataFiltered.length === 0) {
      res.status(400).send({ error: 'Subject or Student not found' });
      return;
    }

    const sumValues = dataFiltered.reduce((acc, cur) => acc + cur.value, 0);

    const result = {
      student: student,
      subject: subject,
      totalValue: sumValues,
    };

    res.send(result);

    //console.log(subjectFiltered);
  } catch (error) {
    // Qualquer erro no decorrer do processo enviar mensagem de erro
    // e status 400
    res.status(400).send({ error: error.message });
  }
});

export default sum;
