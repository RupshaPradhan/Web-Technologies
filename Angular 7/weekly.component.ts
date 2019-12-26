import { Component, OnInit,Input } from '@angular/core';
import * as CanvasJS from '../../../node_modules/canv/canvasjs.min.js';
import { ModalService } from '../modal.service';


@Component({
  selector: 'app-weekly',
  templateUrl: './weekly.component.html',
  styleUrls: ['./weekly.component.css']
})
export class WeeklyComponent implements OnInit {
  @Input() chartData:any;
  @Input() tabCity:any;
  popUpFlag = false;
  modalData: any;
  dataForWeeklyDisplay = [];
  displayImage:any;
  weeklyDate:any;
  weeklyCity:any;
  weeklyTemp:any;
  weeklySummary:any;
  //weeklyPrecipitation: any;
  precipProbability: any;
  precipIntensity: any;
  windSpeed: any;
  humidity: any;
  visibility: any;
  status = [];


  constructor( public modalService: ModalService  ) {  }




ngOnInit( ) {
  this.modalService.apiData5$.subscribe(data => {
    if(this.modalData!=null){
      this.popUpFlag = false;
      this.modalData =data ;
      console.log(this.modalData.latitude);
      console.log(this.modalData.longitude);

      let tym =this.modalData['currently']['time'];
      let tzne =  this.modalData['timezone'];
      let tempDt =  new Date(tym*1000).toLocaleString('en-US', {timeZone: this.modalData['timezone']});
      this.weeklyDate = new Date(tempDt).toLocaleDateString("en-IN");

      this.weeklyCity = this.tabCity;
      this.weeklyTemp = Math.round(this.modalData['currently']['temperature']);
      this.weeklySummary = this.modalData['currently']['summary'];
      let icon= this.modalData['currently']['icon'];
      this.precipIntensity=(Math.round(this.modalData['currently']['precipIntensity']*100)/100);
      this.precipProbability = (Math.round(this.modalData['currently']['precipProbability']*10000)/100)+'%' ;
      this.windSpeed = Math.round(this.modalData['currently']['windSpeed']*100)/100+' mph';
      this.humidity = Math.round(this.modalData['currently']['humidity']*100)+" %";
      this. visibility = (Math.round(this.modalData['currently']['visibility']*100)/100)+' miles';
      if(icon ==="clear-day" || icon ==="clear-night")
        this.displayImage='https://cdn3.iconfinder.com/data/icons/weather-344/142/sun-512.png';
      else if(icon ==="rain")
        this.displayImage='https://cdn3.iconfinder.com/data/icons/weather-344/142/rain-512.png';
      else if(icon==="snow")
        this.displayImage='https://cdn3.iconfinder.com/data/icons/weather-344/142/snow-512.png';
      else if(icon==="sleet")
        this.displayImage='https://cdn3.iconfinder.com/data/icons/weather-344/142/lightning-512.png';
      else if(icon==="wind")
        this.displayImage='https://cdn4.iconfinder.com/data/icons/the-weather-is-nice-today/64/weather_10-512.png';
      else if(icon==="fog")
        this.displayImage='https://cdn3.iconfinder.com/data/icons/weather-344/142/cloudy-512.png';
      else if(icon==="cloudy")
        this.displayImage='https://cdn3.iconfinder.com/data/icons/weather-344/142/cloud-512.png';
      else
        this.displayImage ='https://cdn3.iconfinder.com/data/icons/weather-344/142/sunny-512.png';

    this.popUpFlag = true;}

  });

    for( let i=0; i<8; i++)
    {
        let t = this.chartData.daily.data[i].time;
        let tZone = new Date(t*1000).toLocaleString('en-US', {timeZone:this.chartData.timezone});
        let y= [Math.round(this.chartData.daily.data[i].temperatureLow), Math.round(this.chartData.daily.data[i].temperatureHigh)];
        let dt = new Date(tZone).toLocaleDateString("en-IN");

        let point = {
            unixT:t,
            label: dt,
            x: (8-i)*10,
            y: y
        }
        this.dataForWeeklyDisplay[i] = point;
    }
    this.paintChart();
  }


  closePopUp($event){
    this.popUpFlag =false;
    this.modalData = null;

  }

  paintChart(){

    var openModal = (e) =>
    {
      this.modalData = 1;
      let clickedDate = e.dataPoint.unixT;
      this.modalService.getModalData(this.chartData.latitude, this.chartData.longitude, clickedDate);

    };



    let chart = new CanvasJS.Chart("myWeeklyChart", {

      dataPointWidth: 15,
      exportEnabled: false,

      animationEnabled: true,
      title: {
        text: "Weekly Weather"
      },
      axisX: {
        title: "Days"
      },
      axisY: {
        gridThickness: 0,
        includeZero: false,
        title: "Temperature in Fahrenheit",
        interval: 10,
        suffix: "",
        prefix: ""
      },

      legend: {
        horizontalAlign: "top",
        verticalAlign: "top",
        fontSize: 15
      },

      toolTip:{
        enabled: true,
        animationEnabled: true,
        backgroundColor:"white",
        borderColor:"#90c9e5"
      },



      data: [{

        click: openModal,
        color: "#90c9e5",

        type: "rangeBar",
        showInLegend: true,
        yValueFormatString: "#0.#",
        indexLabel: "{y[#index]}",
        legendText: "Day wise temperature range",
        toolTipContent: "<b>{label}</b>: {y[0]} to {y[1]}",
        dataPoints: this.dataForWeeklyDisplay
      }]
    });
    chart.render();


  }

}
