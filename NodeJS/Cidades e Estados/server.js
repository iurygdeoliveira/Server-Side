/*
############
# REQUIRES #
############
*/

let express = require('express');
let fs = require('fs'); // Habilitar uso do filesystem

/*
##################
# BOOTING SERVER #
##################
*/

const server = express();
server.use(express.json()); // Enable use of JSON objects

/*
 #############
 # CONSTANTS #
 #############
 */
const CIDADES = 'Json_Cidades_Estados_BR/Cidades.json'; // Cities Archive
const ESTADOS = 'Json_Cidades_Estados_BR/Estados.json'; // State Archive

const OUTPUT = 'Output/'; // Folder with Output
const ESTADOS_GERADOS = OUTPUT + 'Estados/';

/*
##########
# HELPER #
##########
*/

function consoleLog(title, data) {
  console.group(title);
  console.log(data);
  console.groupEnd();
}

function read(file) {
  let content = fs.readFileSync(file, 'utf-8');
  return JSON.parse(content);
}

function write(file, data) {
  fs.writeFile(file, JSON.stringify(data), (error) => {
    if (error) {
      consoleLog('Error writing accounts.json file:', error);
    } else {
      // Response
      console.log('Write File');
    }
  });
}

/*
#############
# QUESTIONS #
#############
*/

function GerarEstados(ListaEstados, ListaCidades) {
  ListaEstados.forEach((estado) => {
    let dados = ListaCidades.filter((cidade) => {
      if (cidade.Estado === estado.ID) {
        return cidade;
      }
    });

    write(ESTADOS_GERADOS + estado.Sigla + '.json', dados);
  });
}

function QuantidadeCidades(UF, imprimir) {
  let estado = read(ESTADOS_GERADOS + UF + '.json');

  if (imprimir) {
    console.log('UF: ' + UF + ' - Total de cidades: ' + estado.length);
  }
  return estado.length;
}

function EstadosOrdenadosPorCidades(ListaEstados) {
  let array = [];

  // Populando Vetor
  ListaEstados.forEach((estado) => {
    array.push({
      UF: estado.Sigla,
      Total: QuantidadeCidades(estado.Sigla, false),
    });
  });

  // Ordenando Vetor
  array.sort((a, b) => {
    if (a.Total < b.Total) {
      return 1;
    }
    if (a.Total > b.Total) {
      return -1;
    }
    // a == b
    return 0;
  });

  console.log(array);
}

function NomesMaiores(ListaEstados) {
  let array = [];

  // Populando Vetor
  ListaEstados.forEach((estado) => {
    EstadoObtido = read(ESTADOS_GERADOS + estado.Sigla + '.json');

    EstadoObtido.sort((a, b) => {
      if (a.Nome.length < b.Nome.length) {
        return 1;
      }
      if (a.Nome.length > b.Nome.length) {
        return -1;
      }
      // a == b
      return 0;
    });

    array.push({
      UF: estado.Sigla,
      Nome: EstadoObtido[0].Nome,
    });
  });

  console.log(array);
  return array;
}

function NomesMenores(ListaEstados) {
  let array = [];

  // Populando Vetor
  ListaEstados.forEach((estado) => {
    EstadoObtido = read(ESTADOS_GERADOS + estado.Sigla + '.json');

    EstadoObtido.sort((a, b) => {
      if (a.Nome.length > b.Nome.length) {
        return 1;
      }
      if (a.Nome.length < b.Nome.length) {
        return -1;
      }
      // a == b
      return 0;
    });

    array.push({
      UF: estado.Sigla,
      Nome: EstadoObtido[0].Nome,
    });
  });

  console.log(array);
  return array;
}

function MaiorNome(ListaEstados) {
  ListaEstados.sort((a, b) => {
    if (a.Nome.length < b.Nome.length) {
      return 1;
    }
    if (a.Nome.length > b.Nome.length) {
      return -1;
    }
    // a == b
    return 0;
  });

  console.log(ListaEstados);
}

function MenorNome(ListaEstados) {
  ListaEstados.sort((a, b) => {
    if (a.Nome.length > b.Nome.length) {
      return 1;
    }
    if (a.Nome.length < b.Nome.length) {
      return -1;
    }
    // a == b
    return 0;
  });

  console.log(ListaEstados);
}

/*
#####################
# APPLICATION START #
#####################
*/

// Lendo arquivo de cidades e estados originais
let Cidades = read(CIDADES);
let Estados = read(ESTADOS);

// 1. Gerar Arquivos dos Estados
//GerarEstados(Estados, Cidades);

// 2. Contar Quantidade de Cidades em cada Estado
console.log('####### QUANTIDADE DE CIDADES EM CADA ESTADO #######');
Estados.forEach((estado) => {
  QuantidadeCidades(estado.Sigla, true);
});

// 3. Cinco Estados que mais possuem cidades
// 4. Cinco Estados que menos possuem cidades
console.log('####### ESTADOS COM MAIS E MENOS CIDADES #######');
EstadosOrdenadosPorCidades(Estados);

// 5. Cidade com maior nome no estado
console.log('####### MAIORES NOMES #######');
let ArrayMaiores = NomesMaiores(Estados);

// 6. Cidade com maior nome no estado
console.log('####### MENORES NOMES #######');
let ArrayMenores = NomesMenores(Estados);

// 7. Maior nome de todos
console.log('####### MAIOR NOME #######');
MaiorNome(ArrayMaiores);

// 8. Menor Nome de todos
console.log('####### MENOR NOME #######');
MenorNome(ArrayMenores);
