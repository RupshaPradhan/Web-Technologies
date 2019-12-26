const express = require('express');
const app = express();
var cors = require('cors');
const request = require('request');
const http = require('http');
const https = require('https');
const port =  process.env.PORT || 3000;
var bodyParser = require('body-parser');


app.use(cors());
app.use(bodyParser.json());

app.get('/', (req, res) => {
  res.send({ 'key': 'test' });

});


app.get('/extractLatLonWithUserProvidedAddress/', (req, res) => {
  var street = req.query.street;
  var city = req.query.city;
  var state = req.query.state;
  var url = 'https://maps.googleapis.com/maps/api/geocode/json?address=' + street + ',' + city + ',' + state + '&key=AIzaSyAMmjD4K26LM4NvvsKIIE3MCC0cFqr6PSU';

  https.get(url, (resp) => {
    let data = '';

    resp.on('data', (chunk) => {
      data += chunk;
    });

    resp.on('end', () => {
      return res.send(JSON.parse(data));
    });

  }).on("error", (err) => {
    return res.send(err);
  });

});


app.get('/extractWeatherDetails/', (req, res) => {
  var lat = req.query.lat;
  var lon = req.query.lon;
  var url = 'https://api.darksky.net/forecast/4b6f9cb86ade1cb93bb3bfc25da7fb62/' + lat + ',' + lon;

  https.get(url, (resp) => {
    let data = '';

    resp.on('data', (chunk) => {
      data += chunk;
    });

    resp.on('end', () => {
      return res.send(JSON.parse(data));
    });

  }).on("error", (err) => {
    return res.send(err);
  });

});

app.get('/getAutoCompOptions/', (req, res) => {
  var enteredValue = req.query.enteredValue;
  var url = 'https://maps.googleapis.com/maps/api/place/autocomplete/json?input=' + enteredValue + '&types=(cities)' +
    '&language=en&key=AIzaSyD5R_es_Wtr7f2kYuKx2pLVBStA66Ye6Po';

  https.get(url, (resp) => {
    let data = '';

    resp.on('data', (chunk) => {
      data += chunk;
    });

    resp.on('end', () => {
      console.log(url);
      console.log(data);
      var availableOptionsSize =0;
      var len = Object.keys(JSON.parse(data)['predictions']).length;
      if(len>=5)
          availableOptionsSize = 5;
      else
          availableOptionsSize = len;
      var topResults =[];
      for(var i=0;i<len;i++)
      {
        topResults.push(JSON.parse(data)['predictions'][i]['structured_formatting']['main_text']);
      }
      return res.send({"opt":topResults});
    });

  }).on("error", (err) => {
    return res.send(err);
  });

});


app.get('/getModalData/',(req,res) =>{
  var lat= req.query.lat;
  var long= req.query.long;
  var time= req.query.time;
  var url = 'https://api.darksky.net/forecast/4b6f9cb86ade1cb93bb3bfc25da7fb62/'+lat+','+long+','+time;

  https.get(url, (resp) => {
      let data = '';
  
      resp.on('data', (chunk) => {
        data += chunk;
      });
  
      resp.on('end', () => {
        return res.send(JSON.parse(data));
      });
  
    }).on("error", (err) => {
      return res.send(err);
    });
});






app.get('/getSeal/', (req, res) => {
  var state = req.query.state;

 

  var url = 'https://www.googleapis.com/customsearch/v1?q=Seal%20of%20State%20' +state+'&cx=003876346729283372183:xf0y8fvxzux&'+
            'num=1&searchType=image&key=AIzaSyDWSlYsgprZ7JYh6U53m0GBWMvK9gvp0nU';
 

            https.get(url, (resp) => {
              let data = '';

              resp.on('data', (chunk) => {
                data += chunk;
              });

              resp.on('end', () => {
                if(JSON.parse(data).items.length>0){
                  seal=JSON.parse(data).items[0].link;
                  return res.send(JSON.stringify({'seal':seal}));
                }
                else{
                  return res.send(JSON.stringify({'stat':"error"}));
                }
              });

            }).on("error", (err) => {
              return res.send(err);
            }); 
  
});





// var corsOptions = {
//   origin: '*',
//   optionsSuccessStatus: 200
// }



app.listen(port, () => {
}); 