import express from 'express';

const app = express();

app.get("/", (request, response) => {
    // return response.send("Hello Word");
    return response.json({message: "Hello Word"});
});

app.post("/", (request, response) => {
    // recebeu os dados 
    return response.json({message: "Os dados foram salvos com sucesso"});
});

app.listen(3333, () => console.log("Server is running !"));