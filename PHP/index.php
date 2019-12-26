<?php 
        session_start();
        $lat = "";
        $long= "";

        //current is checked
        if(isset($_POST['isCurrentSet'])=='1')
        {
            
            if(!isset($_POST['isDailySet']))
            {
                
                $lat=$_POST['lat'];
                $long=$_POST['long'];
                $url = "https://api.forecast.io/forecast/4b6f9cb86ade1cb93bb3bfc25da7fb62/".$lat.",".$long."?exclude=minutely,hourly,alerts,flags";
                $_SESSION['rsp'] = json_decode(file_get_contents("$url"));
                //echo $_SESSION['rsp'];
                echo json_encode($_SESSION['rsp']);
                exit();
            }
            else
            {
                $url = "https://api.darksky.net/forecast/4b6f9cb86ade1cb93bb3bfc25da7fb62/".$_POST['lat'].",".$_POST['long'].",".$_POST['time']."?exclude=minutely";
                $_SESSION['dailyResp'] = json_decode(file_get_contents("$url"));
                echo json_encode($_SESSION['dailyResp']);
                exit();
            }
            
        }

        //current is unchecked
        if(isset($_POST['street'])) 
        {
            if(!isset($_POST['isDailySet']))
            {
                $url1 = "https://maps.googleapis.com/maps/api/geocode/xml?address=".urlencode($_POST["street"]).",".urlencode($_POST["city"]).",".urlencode($_POST["state"])."&key="."AIzaSyAMmjD4K26LM4NvvsKIIE3MCC0cFqr6PSU";
                $latlong = file_get_contents("$url1");
                $xml = new SimpleXMLElement($latlong);

                if($xml->status != "OK")
                {   
                    echo '{"response":"ERROR"}';
                    exit();
                }
                else{
                    $lat = (string)$xml->result->geometry->location->lat;
                    $long = (string)$xml->result->geometry->location->lng;

                }
                
                $url2 = "https://api.forecast.io/forecast/4b6f9cb86ade1cb93bb3bfc25da7fb62/".$lat.",".$long."?exclude=minutely,hourly,alerts,flags";
                $_SESSION['userrsp'] = json_decode(file_get_contents("$url2"));
                echo json_encode($_SESSION['userrsp']);
                exit();
            }
            else
            {
                $url = "https://api.darksky.net/forecast/4b6f9cb86ade1cb93bb3bfc25da7fb62/".$_POST['lat'].",".$_POST['long'].",".$_POST['time']."?exclude=minutely";
                $_SESSION['userdailyResp'] = json_decode(file_get_contents("$url"));
                echo json_encode($_SESSION['userdailyResp']);
                exit();
            }
        }
?>


<!DOCTYPE html>
<head>
    <meta charset="utf-8" />
    <title></title>

    <style>
            

            #container2{
                position: absolute;
                top: 332px;
                left: 356px;
                width: 350px;
                height: 28px;
                background: #f6f6f6;
                color: black;
                padding-top:6px;
                text-align: center;
                display: none;
                border-style: solid;
                border-color: #A9A9A9;
                font-size:17px;
            }

            
            #infoTable{
                display: none;
                margin-top: 15px;
            }


            table, th, td {
                border: 2px solid #00c0ff;
                border-collapse: collapse;
            }

            #heading{
                display:None;
                font-size:35px;
                position:absolute;
                top:945px;
                left:565px;
            }

            .box{
                border: 1px solid #808080; 
                color: black; 
            }
            
            #tdhover:hover
            {
                cursor:pointer;
            }

            #up:hover
            {
                cursor:pointer;
            }

            #down:hover
            {
                cursor:pointer;
            }

    </style>
</head>
<body style="text-align:center;">
<script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
<script type="text/javascript">
    var xmlhttp;
    var ctnt=[];
    var lat;
    var long;
    var city="";
    var tzone="";

    if(window.XMLHttpRequest)
    {
        xmlhttp = new XMLHttpRequest();
    }
    else
    {
        xmlhttp =  new ActiveXObject("Microsoft.XMLHTTP");
    }

    function clr()
    {
        document.getElementById("street").disabled =  false;
        document.getElementById("city").disabled =  false;
        document.getElementById("state").disabled =  false; 
        document.getElementById("street").value ="";
        document.getElementById("city").value="";
        document.getElementById("state").value="";
        document.getElementById("current").checked = false;
        document.getElementById('dailyHeader').style.display = 'None';
        document.getElementById('curve_chart').style.display = 'None';
        document.getElementById('container2').style.display = 'None';
        document.getElementById('card').style.display = 'None';
        document.getElementById('infoTable').style.display = 'None';
        document.getElementById('dailyCardOuter').style.display = 'None';
        document.getElementById('down').style.display = 'None';
        document.getElementById('heading').style.display = 'None';
        document.getElementById('down').style.display = 'None';
        document.getElementById('up').style.display = 'None';
        document.getElementById('curve_chart').style.display = 'None';
    }

    function disbl()
    {
        if(document.getElementById("current").checked == true)
        {
            document.getElementById("street").value ="";
            document.getElementById("city").value="";
            document.getElementById("state").value="";
            document.getElementById("street").disabled =  true;
            document.getElementById("city").disabled =  true;
            document.getElementById("state").disabled =  true;
        }
        else
        {
            document.getElementById("street").disabled =  false;
            document.getElementById("city").disabled =  false;
            document.getElementById("state").disabled =  false; 
        }
        
    }
    
    function validate()
    {
        var isCurrentSet= "0";
        if (document.getElementById("current").checked == true)
        {
                    isCurrentSet= "1";
                    var url = "http://ip-api.com/json";
                    xmlhttp.open("GET",url,false); 
                    xmlhttp.send();
                    if (xmlhttp.readyState == 4 && xmlhttp.status === 200)
                    {
                            var json = JSON.parse(xmlhttp.responseText);
                            lat = json.lat;
                            long =  json.lon;
                            city = json.city;
                            document.getElementById("lat").value =  lat;
                            document.getElementById("long").value =  long;
                            url = "index.php";
                            var params = "lat="+lat+"&long="+long+"&isCurrentSet="+isCurrentSet;
                            xmlhttp.open("POST",url , false);
                            xmlhttp.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');
                            xmlhttp.send(params);
                            if(xmlhttp.readyState == 4 && xmlhttp.status == 200)
                            {
                                        data = JSON.parse(xmlhttp.responseText); 
                                        if(data.response =="ERROR")
                                            {
                                                document.getElementById('container2').innerHTML = 'Please check the input address.';
                                                document.getElementById('container2').style.display = 'inline-block';
                                                return;
                                            }
                                        document.getElementById('container2').style.display = 'None';                                       
                                        displayFirst(data,isCurrentSet);
                            }
                    }
                    else
                    {
                            document.getElementById('container2').innerHTML = 'Please check the input address.';
                            document.getElementById('container2').style.display = 'inline-block';
                    }
        }

        var frm =  document.getElementById('locationForm');
        if (document.getElementById("current").checked == false &&  (frm.street.value.trim() =="" || frm.city.value.trim() =="" || frm.state.value == "" ||frm.state.value == "10" ||frm.state.value == "20" ))
        {
            document.getElementById('dailyHeader').style.display = 'None';
            document.getElementById('dailyCardOuter').style.display = 'None';
            document.getElementById('heading').style.display = 'None';
            document.getElementById('curve_chart').style.display = 'None';
            document.getElementById('infoTable').style.display = 'None';
            document.getElementById('card').style.display = 'None';
            document.getElementById('container2').innerHTML = 'Please check the input address.';
            document.getElementById('container2').style.display = 'inline-block';
            
            return false;
        }
        else
        {
            isCurrentSet= "0";
            document.getElementById('container2').style.display = 'none';
            url = "index.php";
            var params = "street="+frm.street.value+"&city="+frm.city.value+"&state="+frm.state.value;
            city = frm.city.value;
            xmlhttp.open("POST",url , false);
            xmlhttp.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');
            xmlhttp.send(params);
            if(xmlhttp.readyState == 4 && xmlhttp.status == 200) 
            {
                    //alert("Received data with self call with user details");
                    data = JSON.parse(xmlhttp.responseText);
                    if(data.response =="ERROR")
                    {
                                document.getElementById('container2').innerHTML = 'Please check the input address.';
                                document.getElementById('container2').style.display = 'inline-block';
                                return;
                    }
                    document.getElementById('container2').style.display = 'None';
                    lat= data.latitude;
                    long= data.longitude;
                    displayFirst(data,isCurrentSet);
            }
            else 
                {
                    document.getElementById('container2').innerHTML = 'Please check the input address.';
                    document.getElementById('container2').style.display = 'inline-block';
                                return;
                }
            
        }
                                   
    }

    function displayFirst(respData,isCurrentSet)
    {
        document.getElementById('dailyHeader').style.display = 'None';
        document.getElementById('curve_chart').style.display = 'None';
        document.getElementById('container2').style.display = 'None';
        document.getElementById('heading').style.display = 'None';
        document.getElementById('dailyCardOuter').style.display = 'None';
        tzone= respData.timezone;
        html_text= "<div style='height:273px;width:449px;float:left;margin-left:25px;'><div style='margin-top:30px;'><span style='font-size: 35px;color:white;'><b>"+city+"</b><br></span></div>";
        html_text+= "<div style='margin-top:2px;'><span style='font-size: 20px;color:white;'>"+respData.timezone+"</span></div>";
        html_text+= "<div style='float:left;height:95px;width: 449px;'><div style='float:left;'><span style='color:white; font-size:74px;'>"+Math.round(respData.currently.temperature)+"</span></div>";
        html_text+=  "<div style='float:left; margin-top: 8px;'><img src='https://cdn3.iconfinder.com/data/icons/virtual-notebook/16/button_shape_oval-512.png' height='10' width='10'></div>";
        html_text+=  "<div style='float:left;margin-top: 22px;'><span style='color:white; font-size:50px;'><b> F </b></span></div></div>";
        html_text+=  "<div style='float:left;height:80px;width: 449px;'><span style='color:white; font-size: 35px; word-wrap: break-word;'><b>"+ respData.currently.summary +"</b></span></div>";       
        html_text+=  "</div>";

        html_text+= "<div style='height:90px;width:474px;float:left;'><div style='height:90px;width:79px;float:left; text-align:center;'><img src='https://cdn2.iconfinder.com/data/icons/weather-74/24/weather-16-512.png' title= 'Humidity' width='40' height='40'><br><span style='font-size:20px;color:white;'>" +respData.currently.humidity+ "</span></div>";
        html_text+= "<div style='height:90px;width:79px;float:left; text-align:center;'><img src='https://cdn2.iconfinder.com/data/icons/weather-74/24/weather-25-512.png' title= 'Pressure' width='40' height='40'><br><span style='font-size:20px;color:white;'>" +respData.currently.pressure+"</span></div>";
        html_text+= "<div style='height:90px;width:79px;float:left; text-align:center;'><img src='https://cdn2.iconfinder.com/data/icons/weather-74/24/weather-27-512.png' title= 'Wind Speed' width='40' height='40'><br><span style='font-size:20px;color:white;'>" +respData.currently.windSpeed+"</span></div>";
        html_text+= "<div style='height:90px;width:79px;float:left; text-align:center;'><img src='https://cdn2.iconfinder.com/data/icons/weather-74/24/weather-30-512.png' title= 'Visibility' width='40' height='40'><br><span style='font-size:20px;color:white;'>" +respData.currently.visibility+"</span></div>";
        html_text+= "<div style='height:90px;width:79px;float:left; text-align:center;'><img src='https://cdn2.iconfinder.com/data/icons/weather-74/24/weather-28-512.png' title= 'CloudCover' width='40' height='40'><br><span style='font-size:20px;color:white;'>" +respData.currently.cloudCover+"</span></div>";
        html_text+= "<div style='height:90px;width:79px;float:left; text-align:center;'><img src='https://cdn2.iconfinder.com/data/icons/weather-74/24/weather-24-512.png' title= 'Ozone' width='40' height='40'><br><span style='font-size:20px;color:white;'>" +respData.currently.ozone+"</span></div>";
        html_text+=  "</div>";

       

       tbl_text = "<div style=\"margin-top: 30px;display: inline-block;\">";
       tbl_text+= "<table style=\"background-color:#adcfe6;color:white;font-size:21px;\">";
       tbl_text+= "<tr><th>Date</th><th>Status</th><th>Summary</th><th>TemperatureHigh</th><th>TemperatureLow</th><th>Wind Speed</th></tr>";
       for(i=0;i<respData.daily.data.length;i++)
       {
            tbl_text+="<tr>";
            tbl_text+="<td>" +new Date(new Date(respData.daily.data[i].time*1000).toLocaleString('en-US', {timeZone: tzone})).toISOString().slice(0,10)+ "</td>";
            icon = "";
            if (respData.daily.data[i].icon=="clear-day" || respData.daily.data[i].icon=="clear-night") {
                icon = "https://cdn2.iconfinder.com/data/icons/weather-74/24/weather-12-512.png";
            } 
            else if (respData.daily.data[i].icon=="rain") {
                icon = "https://cdn2.iconfinder.com/data/icons/weather-74/24/weather-04-512.png";
            } 
            else if (respData.daily.data[i].icon=="snow") {
                icon = "https://cdn2.iconfinder.com/data/icons/weather-74/24/weather-19-512.png";
            } 
            else if (respData.daily.data[i].icon=="sleet") {
                icon = "https://cdn2.iconfinder.com/data/icons/weather-74/24/weather-07-512.png";
            } 
            else if (respData.daily.data[i].icon=="wind") {
                icon = "https://cdn2.iconfinder.com/data/icons/weather-74/24/weather-27-512.png";
            } 
            else if (respData.daily.data[i].icon=="fog") {
                icon = "https://cdn2.iconfinder.com/data/icons/weather-74/24/weather-28-512.png";
            } 
            else if (respData.daily.data[i].icon=="cloudy") {
                icon = "https://cdn2.iconfinder.com/data/icons/weather-74/24/weather-01-512.png";
            } 
            else {
                icon = "https://cdn2.iconfinder.com/data/icons/weather-74/24/weather-02-512.png";
            }
            tbl_text+="<td><img src=\""+icon+"\" height=\"37\" width=\"37\"> </td>";
            tbl_text+="<td id='tdhover'onclick=\"dailyCardBlock("+respData.daily.data[i].time+","+isCurrentSet+");\">"+respData.daily.data[i].summary+" </td>";
            tbl_text+="<td>"+respData.daily.data[i].temperatureHigh+ "</td>";
            tbl_text+="<td>" +respData.daily.data[i].temperatureLow+ "</td>";
            tbl_text+="<td>" +respData.daily.data[i].windSpeed+ "</td></tr>";
       }
       tbl_text += "</table></div><br>";
       document.getElementById('card').innerHTML = html_text;
       document.getElementById('infoTable').innerHTML = tbl_text;
       document.getElementById('card').style.display = 'inline-block';
       document.getElementById('infoTable').style.display = 'inline-block';
    }

       
    function dailyCardBlock(time,isCurrentSet)
    {
        window.scrollTo(0, 0);
        document.getElementById("hiddenTime").value =time;
        document.getElementById('card').style.display = 'None';
        document.getElementById('infoTable').style.display = 'None';
        document.getElementById('container2').style.display = 'None';
        url = "index.php";
        var params =  "lat="+lat+"&long="+long+"&isCurrentSet="+isCurrentSet+"&time="+time+"&isDailySet=True";
        xmlhttp.open("POST",url , false);
        xmlhttp.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');
        xmlhttp.send(params);
        if(xmlhttp.readyState == 4 && xmlhttp.status == 200) 
        {
                dailyData = JSON.parse(xmlhttp.responseText);
                icon = "";
                if (dailyData.currently.icon=="clear-day" || dailyData.currently.icon=="clear-night")
                    icon = "https://cdn3.iconfinder.com/data/icons/weather-344/142/sun-512.png";
                else if (dailyData.currently.icon=="rain")
                    icon = "https://cdn3.iconfinder.com/data/icons/weather-344/142/rain-512.png";
                else if (dailyData.currently.icon=="snow")
                    icon = "https://cdn3.iconfinder.com/data/icons/weather-344/142/snow-512.png";
                else if (dailyData.currently.icon=="sleet")
                    icon = "https://cdn3.iconfinder.com/data/icons/weather-344/142/lightning-512.png";
                else if (dailyData.currently.icon=="wind")
                    icon = "https://cdn4.iconfinder.com/data/icons/the-weather-is-nice-today/64/weather_10-512.png";
                else if (dailyData.currently.icon=="fog")
                    icon = "https://cdn3.iconfinder.com/data/icons/weather-344/142/cloudy-512.png";
                else if (dailyData.currently.icon=="cloudy")
                    icon = "https://cdn3.iconfinder.com/data/icons/weather-344/142/cloud-512.png";
                else if(dailyData.currently.icon=="partly-cloudy-day" || dailyData.currently.icon=="partly-cloudy-night")
                    icon = "https://cdn3.iconfinder.com/data/icons/weather-344/142/sunny-512.png";

                precip =dailyData.currently.precipIntensity;
                precipVal = "";
                if(precip<=0.001)                   
                    precipVal="None";
                else if(precip<=0.015)
                    precipVal="Very Light";
                else if(precip <=0.05)
                    precipVal="Light";
                else if(precip <=0.1)
                    precipVal="Moderate";
                else if(precip>1)
                    precipVal="heavy";

                precipProb = 100*dailyData.currently.precipProbability;
                humidity=100*dailyData.currently.humidity;
                sunriseTemp =parseInt( new Date(dailyData.daily.data[0].sunriseTime*1000).toLocaleString('en-US',  {timeZone: ""+tzone+"", hour: 'numeric', hour12: false }));
                sunsetTemp = parseInt(new Date(dailyData.daily.data[0].sunsetTime*1000).toLocaleString( 'en-US',{timeZone: ""+tzone+"", hour: 'numeric', hour12: false }));
                
                hoursSunrise = (sunriseTemp  % 12);
                if(hoursSunrise==0)
                {
                    hoursSunrise = 12;
                }
                suffix1 = ((sunriseTemp >= 12) && (sunriseTemp < 24))? 'PM' : 'AM';
               

                hoursSunset = (sunsetTemp  % 12);
                if(hoursSunset==0)
                {
                    hoursSunset = 12;
                }
                suffix2 = ((sunsetTemp >= 12) && (sunsetTemp < 24))? 'PM' : 'AM';

                
                var daily_txt="";
                daily_txt+="<div id=\"dail\" style=\"width:474px; height:352px;\">";
                daily_txt+="<div id=\"l1\" style=\"height: 180px; width:237px;float:left;margin-left:20px;\">";
                daily_txt+="<div id=\"leftUp\" style=\"width:237px;height:75px;font-size:30px;margin-top:30px;color:white;word-wrap: break-word;\">"+dailyData.currently.summary+"</div>";
                daily_txt+="<div style=\"float:left;height:100px;width: 300px;\">";
                daily_txt+="<div style=\"float:left;\"><span style=\"color:white; font-size:74px;\">"+Math.round(dailyData.currently.temperature)+"</span></div>";
                daily_txt+="<div style=\"float:left; margin-top: 8px;\"><img src=\" https://cdn3.iconfinder.com/data/icons/virtual-notebook/16/button_shape_oval-512.png\" height=\"10\" width=\"10\"></div>";
                daily_txt+="<div style=\"float:left;margin-top: 22px;\"><span style=\"color:white; font-size:50px;\"><b> F </b></span></div>"; 
                daily_txt+="</div>";
                daily_txt+="</div>";
                daily_txt+="<div id=\"r1\" style=\"height: 180px; width:217px;float:left;\">";
                daily_txt+="<img src=\""+icon+"\" width=\"170px\" height=\"170px\">";
                daily_txt+="</div>";
                daily_txt+="<div id=\"bottom\" style=\"width:474px;float:left;\">";
                daily_txt+="<div id=\"bottomleft\" style=\"width:310px;height:28px;text-align:right;float:left\">";
                daily_txt+="<span style=\"font-size:20px;color:white\">Precipitation:<span>";
                daily_txt+="</div>";
                daily_txt+="<div id=\"bottomright\" style=\"width:164px;height:28px;text-align:left;float:left;\">";
                daily_txt+="<span style=\"font-size:26px;color:white\"><b>"+precipVal+"</b><span>";
                daily_txt+="</div>";
                daily_txt+="<div id=\"bottomleft\" style=\"width:310px;height:28px;text-align:right;float:left\">";
                daily_txt+="<span style=\"font-size:20px;color:white\">Chance of rain:<span>";
                daily_txt+="</div>";
                daily_txt+="<div id=\"bottomright\" style=\"width:164px;height:28px;text-align:left;float:left;\">";
                daily_txt+="<span style=\"font-size:26px;color:white\"><b>"+Math.round(precipProb)+"<span style=\"font-size:16px;\">%</span></b><span>";
                daily_txt+="</div>";
                daily_txt+="<div id=\"bottomleft\" style=\"width:310px;height:28px;text-align:right;float:left\">";
                daily_txt+="<span style=\"font-size:20px;color:white\">Wind Speed:<span>";
                daily_txt+="</div>";
                daily_txt+="<div id=\"bottomright\" style=\"width:164px;height:28px;text-align:left;float:left;\">";
                daily_txt+="<span style=\"font-size:26px;color:white\"><b>"+dailyData.currently.windSpeed+"<span style=\"font-size:16px;\">mph</span></b><span>";
                daily_txt+="</div>";
                daily_txt+="<div id=\"bottomleft\" style=\"width:310px;height:28px;text-align:right;float:left\">";
                daily_txt+="<span style=\"font-size:20px;color:white\">Humidity:<span>";
                daily_txt+="</div>";
                daily_txt+="<div id=\"bottomright\" style=\"width:164px;height:28px;text-align:left;float:left;\">";
                daily_txt+="<span style=\"font-size:26px;color:white\"><b>"+Math.round(humidity)+"<span style=\"font-size:16px;\">%</span></b><span>";
                daily_txt+="</div>";
                daily_txt+="<div id=\"bottomleft\" style=\"width:310px;height:28px;text-align:right;float:left\">";
                daily_txt+="<span style=\"font-size:20px;color:white\">Visibility:<span>";
                daily_txt+="</div>";
                daily_txt+="<div id=\"bottomright\" style=\"width:164px;height:28px;text-align:left;float:left;\">";
                daily_txt+="<span style=\"font-size:26px;color:white\"><b>"+dailyData.currently.visibility+"<span style=\"font-size:16px;\">mi</span></b><span>";
                daily_txt+="</div>";
                daily_txt+="<div id=\"bottomleft\" style=\"width:310px;height:28px;text-align:right;float:left\">";
                daily_txt+="<span style=\"font-size:20px;color:white\">Sunrise/Sunset:<span>";
                daily_txt+="</div>";
                daily_txt+="<div id=\"bottomright\" style=\"width:164px;height:28px;text-align:left;float:left;\">";
                daily_txt+="<span style=\"font-size:26px;color:white\"><b>"+hoursSunrise+"</span><span style=\"font-size:15px;color:white;\">"+suffix1+"/<span><span style=\"font-size:26px;color:white\"><b>"+hoursSunset+"</span><span style=\"font-size:15px;color:white;\">"+suffix2+"<span></b><span>";
                daily_txt+="</div>";
                daily_txt+="</div>";
                daily_txt+="</div>";


               plotData = dailyData.hourly.data;
               google.charts.load('current', {packages: ['corechart', 'line']});
               google.charts.setOnLoadCallback(drawChart);
                
                for (k = 0; k < plotData.length; k++) 
                    ctnt[k] = [k,plotData[k].temperature];
                document.getElementById('dailyHeader').style.display = 'inline-block';
                
                document.getElementById('infoTable').style.display = 'None';
                document.getElementById('dailyCardOuter').innerHTML = daily_txt;
                document.getElementById('dailyCardOuter').style.display = 'inline-block';
                document.getElementById('up').style.display = 'None';
                document.getElementById('down').style.display = 'inline-block';
                document.getElementById('heading').style.display = 'inline-block';

        }
        else
            {
                document.getElementById('container2').innerHTML = 'Please check the input address.';
                    document.getElementById('container2').style.display = 'inline-block';
                                return;
            }
    }


    function upEvent()
    {
        //alert('Squeze');
        document.getElementById('down').style.display = 'inline-block';
        document.getElementById('up').style.display = 'none';
        document.getElementById('curve_chart').style.display = 'none';

    }


    function downEvent()
    {
        // alert('expand');
        document.getElementById('down').style.display = 'None';
        document.getElementById('up').style.display = 'inline-block';
        document.getElementById('curve_chart').style.display = 'inline-block';
    }


    function drawChart() 
    {
        var data = new google.visualization.DataTable();
        data.addColumn('number', 'X');
        data.addColumn('number', 'T');
        data.addRows(ctnt);
        var options = 
            {
                hAxis: {
                        title: 'Time'
                       },
                vAxis: {
                        title: 'Temperature'
                       }
                ,width:670
                ,colors:["#b9dae3"]
            };

        var chart = new google.visualization.LineChart(document.getElementById('curve_chart'));
        chart.draw(data, options);
    }
</script> 

<div style="width: 1050px; text-align: center;position: relative;display: inline-block;">
	<div id="formblock" style="width: 840px;height: 280px;background-color: green;border-radius: 10px;display: inline-block;">
		<h1 style="color:white;"><i>Weather Search</i></h1>
        <form id="locationForm"  method = "POST" class = "container1" onSubmit="return validate()" > 
           
           <div class = "lft" style="float: left;height: 160px;width: 400px;">
				<div id="lft1" style=" float: left;margin-left: 43px; text-align: left;height: 160px;width: 50px;">
                <div style="height: 30px;"><span style="color:white;font-size: 21px;">Street</span></div>
                <div style="height: 30px;"><span style="color:white;font-size: 21px;">City</span></div>
                <div style="height: 30px;"><span style="color:white;font-size: 21px;">State</span></div>
				</div>

				<div id=lft2 style="float: left;width: 307px;">
                    <div style="float: left;height: 160px;width: 150px;margin-top: 4px;">
                        <div style="height: 30px;"><input type="text" name="street" id="street" value = ""></div>
                        <div style="height: 30px;"><input type="text" id = "city" name="city" value = ""></div>
                        <div style="height: 30px;"><select id="state" name="state" style="width: 250px;margin-left: -4px;border-radius: 5px;">
                        <option value="20" selected>State</option>
                            <option value='10' disabled>------------------------------------------</option>
                            <option value="AL">Alabama</option>
                            <option value="AK">Alaska</option>
                            <option value="AZ">Arizona</option>
                            <option value="AR">Arkansas</option>
                            <option value="CA">California</option>
                            <option value="CO">Colorado</option>
                            <option value="CT">Connecticut</option>
                            <option value="DE">Delaware</option>
                            <option value="DC">District Of Columbia</option>
                            <option value="FL">Florida</option>
                            <option value="GA">Georgia</option>
                            <option value="HI">Hawaii</option>
                            <option value="ID">Idaho</option>
                            <option value="IL">Illinois</option>
                            <option value="IN">Indiana</option>
                            <option value="IA">Iowa</option>
                            <option value="KS">Kansas</option>
                            <option value="KY">Kentucky</option>
                            <option value="LA">Louisiana</option>
                            <option value="ME">Maine</option>
                            <option value="MD">Maryland</option>
                            <option value="MA">Massachusetts</option>
                            <option value="MI">Michigan</option>
                            <option value="MN">Minnesota</option>
                            <option value="MS">Mississippi</option>
                            <option value="MO">Missouri</option>
                            <option value="MT">Montana</option>
                            <option value="NE">Nebraska</option>
                            <option value="NV">Nevada</option>
                            <option value="NH">New Hampshire</option>
                            <option value="NJ">New Jersey</option>
                            <option value="NM">New Mexico</option>
                            <option value="NY">New York</option>
                            <option value="NC">North Carolina</option>
                            <option value="ND">North Dakota</option>
                            <option value="OH">Ohio</option>
                            <option value="OK">Oklahoma</option>
                            <option value="OR">Oregon</option>
                            <option value="PA">Pennsylvania</option>
                            <option value="RI">Rhode Island</option>
                            <option value="SC">South Carolina</option>
                            <option value="SD">South Dakota</option>
                            <option value="TN">Tennessee</option>
                            <option value="TX">Texas</option>
                            <option value="UT">Utah</option>
                            <option value="VT">Vermont</option>
                            <option value="VA">Virginia</option>
                            <option value="WA">Washington</option>
                            <option value="WV">West Virginia</option>
                            <option value="WI">Wisconsin</option>
                            <option value="WY">Wyoming</option>

                        </select></div>
                    </div>
                    <input type="hidden" name="lat" id="lat"/>
                    <input type="hidden" name="long" id="long"/>
                    <input type="hidden" id="hiddenTime" name="hiddenTime"/>
				</div>
			</div>
                    
		   
				            
        
            <div class = "mdl"  style="border-radius: 1.5px; width: 5px;float: left;background-color: white;height: 128px;"></div>
        
            <div class = "rht" style="height: 152px;float: left; width: 427px;color: white;font-size: 18px;">
                <input type="checkbox" id = "current" onclick ="disbl();"><b>Current Location</b><br>
            </div>
        
            <div class = "btn" style="margin-left: -214px;clear: left">            
                <button type="button"  id= "search" name="search" value="search" class="bt" onClick = "validate()" style="border-radius: 5px;">search</button>&nbsp;
                <button  type="button" id = "reset" class="bt" onClick = "clr();" style="border-radius: 5px;">clear</button>
            </div>
        
        </form>
    </div>
        
        <div id="container2"></div>





        <div id="card" style="position: relative;height: 352px;width: 474px;background:#00c0ff;text-align:left;clear:both;border-radius: 10px;margin-top:45px;display:None">
             <!-- <div style="height:300px;width:300px;float:left;margin-left:25px;">

                <div style="margin-top:30px;"><span style="font-size: 35px;color:white;"><b>Los Angeles</b><br></span></div>
                <div style="margin-top:2px;"><span style="font-size: 20px;color:white;">California</span></div>

                <div style="float:left;height:100px;width: 300px;">
                    <div style="float:left;"><span style="color:white; font-size:74px;">32</span></div>
                    <div style="float:left; margin-top: 8px;"><img src=" https://cdn3.iconfinder.com/data/icons/virtual-notebook/16/button_shape_oval-512.png" height="10" width="10"></div>
                    <div style="float:left;margin-top: 22px;"><span style="color:white; font-size:50px;"><b> F </b></span></div> 
                </div>

                <div style="float:left;height:60px;width: 300px;margin-top:-15px;"><span style="color:white; font-size: 35px;"><b>Clear</b></span></div>       

            </div>
            

            <div style="height:90px;width:474px;float:left;margin-top:-40px;">
                <div style="height:90px;width:79px;float:left; text-align:center;">
                    <img src="https://cdn2.iconfinder.com/data/icons/weather-74/24/weather-16-512.png" title= 'Humidity' width='50' height='50'><br>
                    <span style="font-size:20px;color:white;">34</span>
                </div>
                <div style="height:90px;width:79px;float:left; text-align:center;">
                    <img src="https://cdn2.iconfinder.com/data/icons/weather-74/24/weather-25-512.png" title= 'Pressure' width='50' height='50'><br>
                    <span style="font-size:20px;color:white;">34</span>
                </div>
                <div style="height:90px;width:79px;float:left; text-align:center;">
                    <img src="https://cdn2.iconfinder.com/data/icons/weather-74/24/weather-27-512.png" title= 'Wind Speed' width='50' height='50'><br>
                    <span style="font-size:20px;color:white;">34</span>
                </div>
                <div style="height:90px;width:79px;float:left; text-align:center;">
                    <img src="https://cdn2.iconfinder.com/data/icons/weather-74/24/weather-30-512.png" title= 'Visibility' width='50' height='50'><br>
                    <span style="font-size:20px;color:white;">34</span>
                </div>
                <div style="height:90px;width:79px;float:left; text-align:center;">
                    <img src="https://cdn2.iconfinder.com/data/icons/weather-74/24/weather-28-512.png" title= 'CloudCover' width='50' height='50'><br>
                    <span style="font-size:20px;color:white;">34</span>
                </div>
                <div style="height:90px;width:79px;float:left; text-align:center;">
                    <img src="https://cdn2.iconfinder.com/data/icons/weather-74/24/weather-24-512.png" title= 'Ozone' width='50' height='50'><br>
                    <span style="font-size:20px;color:white;">34</span>
                </div>

            </div>



        </div> --> 

        </div>

        
    
    
    
    
        
        
        <div id="infoTable"></div>
        <div id="dailyHeader" style="margin-left:-248px; ;width:inherit;height:70px; display:None;margin-top: 24px;background:white;">
                <span style="margin-left:270px;font-size:42px;"><b>Daily Weather Detail</b></span>
        </div>
        <div id="dailyCardOuter" style="display: None;position: relative;height: 381px;width: 474px;background:#b9dae3;text-align:left;clear:both;border-radius: 10px;margin-top:0px;">
            
            <!-- <div id= "daily2" style="width:474px;height: 352px">
                <div id="l1" style="height: 180px; width:237px;float:left;margin-left:20px;">
                    <div id="leftUp" style="width:237px;height:40px;font-size:30px;margin-top:30px">Clear</div>
                    <div style="float:left;height:100px;width: 300px;">
                        <div style="float:left;"><span style="color:white; font-size:74px;">32</span></div>
                        <div style="float:left; margin-top: 8px;"><img src=" https://cdn3.iconfinder.com/data/icons/virtual-notebook/16/button_shape_oval-512.png" height="10" width="10"></div>
                        <div style="float:left;margin-top: 22px;"><span style="color:white; font-size:50px;"><b> F </b></span></div> 
                    </div>
                </div>

                <div id="r1" style="height: 180px; width:217px;float:left;">
                    <img src="https://cdn3.iconfinder.com/data/icons/weather-344/142/sun-512.png" width="170px" height="170px">
                </div>

                <div id="bottom" style="width:474px;float:left;">
                    <div id="bottomleft" style="width:310px;height:28px;text-align:right;float:left">
                        <span style="font-size:20px;color:white">Precipitation:<span>
                    </div>
                    
                    <div id="bottomright" style="width:164px;height:28px;text-align:left;float:left;">
                        <span style="font-size:26px;color:white">********<span>
                    </div>

                    <div id="bottomleft" style="width:310px;height:28px;text-align:right;float:left">
                        <span style="font-size:20px;color:white">Chance of rain:<span>
                    </div>
                    
                    <div id="bottomright" style="width:164px;height:28px;text-align:left;float:left;">
                        <span style="font-size:26px;color:white">********<span>
                    </div>

                    <div id="bottomleft" style="width:310px;height:28px;text-align:right;float:left">
                        <span style="font-size:20px;color:white">Wind Speed:<span>
                    </div>
                    
                    <div id="bottomright" style="width:164px;height:28px;text-align:left;float:left;">
                        <span style="font-size:26px;color:white">********<span>
                    </div>

                    <div id="bottomleft" style="width:310px;height:28px;text-align:right;float:left">
                        <span style="font-size:20px;color:white">Humidity:<span>
                    </div>
                    
                    <div id="bottomright" style="width:164px;height:28px;text-align:left;float:left;">
                        <span style="font-size:26px;color:white">********<span>
                    </div>

                    <div id="bottomleft" style="width:310px;height:28px;text-align:right;float:left">
                        <span style="font-size:20px;color:white">Visibility:<span>
                    </div>
                    
                    <div id="bottomright" style="width:164px;height:28px;text-align:left;float:left;">
                        <span style="font-size:26px;color:white">********<span>
                    </div>

                    <div id="bottomleft" style="width:310px;height:28px;text-align:right;float:left">
                        <span style="font-size:20px;color:white">Sunrise/Sunset:<span>
                    </div>
                    
                    <div id="bottomright" style="width:164px;height:28px;text-align:left;float:left;">
                        <span style="font-size:26px;color:white">********<span>
                    </div>
                </div>
            </div> -->

        
        </div>

        <div id="heading" style="display:None;font-size:42px;top:771px;left:340px;"><b>Days Hourly Weather</b><br>
            <div id="up" style="font-size:35px;position:relative;top:16px;left:9px;"><img src = "https://cdn0.iconfinder.com/data/icons/navigation-set-arrows-part-one/32/ExpandLess-512.png" height="75px" width="75px" onclick ="upEvent();"></div>
            <div id="down" style="font-size:35px;position:relative;top:9px;left:7px;"><img src = "https://cdn4.iconfinder.com/data/icons/geosm-e-commerce/18/point-down-512.png" height="75px" width="75px" onclick ="downEvent();"></div>     
        </div>
        <div id="curve_chart" style=" position:absolute; top:909px; left:193px; display:None;"></div>
</div>


</body>
</html>