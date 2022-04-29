const express = require ('express')
const app = express()
const port = 3300

app.use(express.static('public'))

app.use(function(req, res){
    res.status(404)
    res.send("Not Found")
})

app.listen(port, () => {
  console.log(`Example app listening at http://localhost:${port}`)
})
