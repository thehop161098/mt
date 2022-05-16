const http = require("http");
const express = require("express");
const app = express();
const runSocket = require("./socket");
const { port } = require("./config/Config");
const port_name = process.env.PORT || port;
const server = http.createServer(app);

const io = require("socket.io")(server);
runSocket(io);

server.listen(port_name);
console.log("server listen port " + port_name);
