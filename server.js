// Common to server and server_test- start
const restify = require('restify')
const config = require('config')
const listenOnPort = config.get('listenOnPort')

const server = restify.createServer()

server.get('/', (req, res, next) => {
  res.json({success: true})
})
// Common to server and server_test- stop

// Common to server and server_test- start
server.listen(listenOnPort, () => {
  console.log('Using configuration: %s', config.get('configName'))
  console.log('%s listening at %s', server.name, server.url)
})

