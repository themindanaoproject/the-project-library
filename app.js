const express = require ('express')
const app = express()
var cors = require('cors')
const port = 5900
const fs = require('fs')
const DIR = __dirname

app.use(cors())

app.use(express.static('public'))
app.use(express.json())

app.put('/update',function(req,res){
    if (undefined===req.body.path||undefined===req.body.data) {
        res.status(400)
        res.json({status:400,error:'invalid payload'})
        return
    }
    let concretePath = DIR+'/public/data'+req.body.path
    if (!fs.existsSync(concretePath)) {
        res.status(400)
        res.json({status:404,error:'path not found'})
        return
    }
    /**
     * @TODO: File update happens here
     */
    res.json({status:200})
})

app.use(function(req,res){
    res.status(404)
    res.json({error:'resource not found'})
})

app.listen(port, () => {
  console.log(`The Project Library listening at http://localhost:${port}`)
})
