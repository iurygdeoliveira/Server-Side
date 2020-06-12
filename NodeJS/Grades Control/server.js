/*
###########
# IMPORTS #
###########
*/

import express from 'express';
import { promises } from 'fs';
import add from './source/routes/add.js';
import update from './source/routes/update.js';
import remove from './source/routes/remove.js';
import consult from './source/routes/consult.js';
import sum from './source/routes/sum.js';
import avg from './source/routes/avg.js';
import best from './source/routes/best.js';
import helper from './source/helpers/config.js';

/*
############
# CONSTANT #
############
*/

const { readFile } = promises;
global.gradesFile = './source/json/grades.json';

/*
##################
# BOOTING SERVER #
##################
*/

const server = express();
server.use(express.json()); // Enable use of JSON objects
server.use(helper.endpoints.grades, add); // Enable route add
server.use(helper.endpoints.grades, update); // Enable route update
server.use(helper.endpoints.grades, remove); // Enable route remove
server.use(helper.endpoints.grades, consult); // Enable route consult
server.use(helper.endpoints.grades, sum); // Enable route sum
server.use(helper.endpoints.grades, avg); // Enable route avg
server.use(helper.endpoints.grades, best); // Enable route best

server.listen(3000, async () => {
  try {
    console.log('Reading grades.json file');
    await readFile(global.gradesFile, 'utf-8');
    console.log('API START');
  } catch (error) {
    console.log('error: ' + error.message);
  }
});
