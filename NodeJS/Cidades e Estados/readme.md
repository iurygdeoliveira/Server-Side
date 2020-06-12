<p align="center">
  <img src="https://github.com/iurygdeoliveira/Back-End/blob/master/NodeJS/Cidades%20e%20Estados/img/Manipula%C3%A7%C3%A3o%20de%20Arquivos%20JSON.jpg">
</p>

## Desenvolvedor (Developer):

[<img src="https://avatars3.githubusercontent.com/u/30157522?s=460&u=30d3397df3e4655b6fa8047ac27052569cf7db78&v=4" width=115><br><sub>Iury Gomes de Oliveira</sub>](https://github.com/iurygdeoliveira)

# Descrição do Projeto (Project Description)

## Status

> Status do Projeto: Concluido :heavy_check_mark:

> Project Status: Concluded :heavy_check_mark:

## Demonstração em Vídeo (Video Demo)

> Link para vídeo demonstrativo: https://youtu.be/g1TphKbSh-Y

> Demo video link : https://youtu.be/g1TphKbSh-Y

## Objetivos

Exercitar os seguintes conceitos:

- Criação de um projeto Node.js.
- Manipulação de arquivos.
- Manipulação de objetos JSON.

## Enunciado

Criar um projeto Node.js para realizar a criação de alguns métodos e processamento de arquivos JSON.

## Atividades

O desafio consiste em baixar os arquivos Cidades.json e Estados.json do link a seguir (https://github.com/felipefdl/cidades-estados-brasil-json (Links para um site externo.)) e colocá-los dentro do seu projeto. O arquivo Estados.json possui uma listagem com todos os estados do Brasil, cada um representado por um ID. No arquivo Cidades.json estão listadas todas as cidades do Brasil, com seu respectivo estado representando pelo ID fazendo referência ao arquivo Estados.json.

O projeto deverá desempenhar as seguintes atividades:

1 - Implementar um método que irá criar um arquivo JSON para cada estado representado no arquivo Estados.json, e o seu conteúdo será um array das cidades pertencentes aquele estado, de acordo com o arquivo Cidades.json. O nome do arquivo deve ser o UF do estado, por exemplo: MG.json.

2- Criar um método que recebe como parâmetro o UF do estado, realize a leitura do arquivo JSON correspondente e retorne a quantidade de cidades daquele estado.

3- Criar um método que imprima no console um array com o UF dos cinco estados que mais possuem cidades, seguidos da quantidade, em ordem decrescente. Utilize o método criado no tópico anterior. Exemplo de impressão: [“UF - 93”, “UF - 82”, “UF - 74”, “UF - 72”, “UF - 65”]

4 - Criar um método que imprima no console um array com o UF dos cinco estados que menos possuem cidades, seguidos da quantidade, em ordem decrescente. Utilize o método criado no tópico anterior. Exemplo de impressão: [“UF - 30”, “UF - 27”, “UF - 25”, “UF - 23”, “UF - 21”]

5 - Criar um método que imprima no console um array com a cidade de maior nome de cada estado, seguida de seu UF. Em caso de empate, considerar a ordem alfabética para ordená-los e então retornar o primeiro. Por exemplo: [“Nome da Cidade – UF”, “Nome da Cidade – UF”, ...].

6 - Criar um método que imprima no console um array com a cidade de menor nome de cada estado, seguida de seu UF. Em caso de empate, considerar a ordem alfabética para ordená-los e então retorne o primeiro. Por exemplo: [“Nome da Cidade – UF”, “Nome da Cidade – UF”, ...].

7 - Criar um método que imprima no console a cidade de maior nome entre todos os estados, seguido do seu UF. Em caso de empate, considerar a ordem alfabética para ordená-los e então retornar o primeiro. Exemplo: “Nome da Cidade - UF".

8 - Criar um método que imprima no console a cidade de menor nome entre todos os estados, seguido do seu UF. Em caso de empate, considerar a ordem alfabética para ordená-los e então retornar o primeiro. Exemplo: “Nome da Cidade - UF".

O projeto ao ser executado, deve realizar os cinco métodos em sequência, imprimindo os resultados em console e depois finalizando a execução.
