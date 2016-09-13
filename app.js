let bodyParser = require('body-parser')
let config = require('config')
let request = require('request')

let express = require('express')
let app = express()

app.use(express.static('public'))
app.use(express.static('node_modules/bootstrap/dist'))
app.use(bodyParser.urlencoded({extended: true}))

app.get('/', function (req, res) {
  res.render('index.pug', {bookwerx_coreURL: config.get('bookwerx_coreURL')})
})

app.get('/api', function (req, res) {

  exerciseAPI().then((result)=>{
    console.log("time to render.")
    res.render('api.jade', {title: 'API', result:result})
  })


})

let port = config.get('port')
var listener = app.listen(port, () => {
  console.log('Using configuration: %s', config.get('configName'))
  console.log('Redstar listening at %s', listener.address().port)
})