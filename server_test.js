// Common to server and server_test- start
const restify = require('restify')
const config = require('config')
const listenOnPort = config.get('listenOnPort')

const server = restify.createServer()

server.get('/', (req, res, next) => {
  res.json({success: true})
})
// Common to server and server_test- stop

if (!config.get('enableTest')) {
  let msg = 'Configuration ' + config.get('configName') + ' does not allow testing.'
  throw new Error(msg)
}

// We want to start the server listening and ensure that's it's up and running _before_ we perform
// any test or shutdown.
new Promise( (resolve) => {
  server.listen(listenOnPort, () => {
    console.log('%s listening at %s', server.name, server.url)
    resolve(true)
  })
})

.then(result => {
  console.log('All tests passed. No errors.')
  process.exit()
})

.catch((e) => {
  console.log('error=%j', e)
})
