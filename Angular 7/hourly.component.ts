import { Component, OnInit, Input } from '@angular/core';
import { Chart } from 'chart.js';
import { FormBuilder, Validators } from "@angular/forms";

@Component({
  selector: 'app-hourly',
  templateUrl: './hourly.component.html',
  styleUrls: ['./hourly.component.css']
})
export class HourlyComponent implements OnInit {
  @Input() chartData : any;
  chart: Chart;
  temp = [];
  pres = [];
  hum = [];
  ozone = [];
  visib = [];
  winds = [];
  selected: any;
  instanceWiseData: any;
  lbl: any;
  xLabel: any;
  yLabel: any;
  selectionOptions: any = ['Pressure', 'Humidity', 'Ozone','Visibility','Wind Speed'];

  constructor(public fb: FormBuilder) { }

  registrationForm = this.fb.group({
    selected: ['']
  })

  changeSelection(e) {
    this.selected = e.target.value;
    console.log(e);
    //alert('User selected '+this.selected);
    if(this.selected ==='')
    {
      this.lbl = 'temperature';
      this.xLabel =  'Time difference from current hour';
      this.yLabel= 'Fahrenheit';
      this.instanceWiseData = this.temp;
    }
    else if(this.selected ==='1: Pressure')
    {
      this.lbl = 'pressure';
      this.xLabel =  'Time difference from current hour';
      this.yLabel= 'Millibars';
      this.instanceWiseData = this.pres;
    }
    else if(this.selected ==='2: Humidity')
    {
      this.lbl = 'humidity';
      this.xLabel =  'Time difference from current hour';
      this.yLabel = '% Humidity';
      this.instanceWiseData = this.hum;
    }
    else if(this.selected === '3: Ozone')
    {
      this.lbl = 'ozone';
      this.xLabel =  'Time difference from current hour';
      this.yLabel= 'Dobson Units';
      this.instanceWiseData = this.ozone;
    }
    else if(this.selected === '4: Visibility')
    {
      this.lbl = 'visibility';
      this.xLabel =  'Time difference from current hour';
      this.yLabel= 'Miles (Maximum 10)';
      this.instanceWiseData = this.visib;
    }
    else
    {
      this.lbl = 'windSpeed';
      this.xLabel =  'Time difference from current hour';
      this.yLabel= 'Miles per Hour';
      this.instanceWiseData = this.winds;
    }
    console.log(this.instanceWiseData);
    this.paintChart();
    }

  ngOnInit() {
    for ( let i = 0; i < 24; i++ )
    {
    this.temp.push(Math.round(this.chartData.hourly.data[i].temperature));
    this.pres.push(this.chartData.hourly.data[i].pressure);
    this.hum.push(this.chartData.hourly.data[i].humidity);
    this.ozone.push(this.chartData.hourly.data[i].ozone);
    this.visib.push(this.chartData.hourly.data[i].visibility);
    this.winds.push(this.chartData.hourly.data[i].windSpeed);
    }

    this.instanceWiseData = this.temp;
    this.lbl = 'temperature';
    this.xLabel =  'Time difference from current hour';
    this.yLabel= 'Fahrenheit';

    this.paintChart();




  }

  paintChart()
  {
    let dMin = Math.min.apply(Math, this.instanceWiseData);
    let dMax = Math.max.apply(Math, this.instanceWiseData);
    console.log(dMax);
    console.log(dMin);
    let sMin = (-(dMax-dMin) * 0.23 )+ dMin;
    let sMax = ((dMax-dMin) * 0.23 )+ dMax;

      let ctx = 'myChart';
      if(this.chart)
      {
        this.chart.destroy();
      }

      this.chart=(new Chart(ctx, {
        type: 'bar',
        data: {
            labels: ["0", "1", "2", "3", "4", "5", "6", "7", "8", "9", "10", "11", "12", "13", "14", "15", "16", "17", "18", "19", "20", "21", "22", "23"],
            datasets: [{
                label: this.lbl,
                data: this.instanceWiseData,
                backgroundColor: ['#90c9e5','#90c9e5','#90c9e5','#90c9e5','#90c9e5','#90c9e5','#90c9e5','#90c9e5','#90c9e5',
                '#90c9e5','#90c9e5','#90c9e5','#90c9e5','#90c9e5','#90c9e5','#90c9e5','#90c9e5','#90c9e5','#90c9e5','#90c9e5',
                '#90c9e5','#90c9e5','#90c9e5','#90c9e5'
                ],
                borderWidth: 1
            }]
        },
        options: {
          legend: {
            onClick: null
          },
          scales: {
            yAxes: [{
              scaleLabel: {
                display: true,
                labelString: this.yLabel
              },
              ticks: {
                suggestedMin: sMin,
                suggestedMax: sMax
            }
            }],
            xAxes: [{
              scaleLabel: {
                display: true,
                labelString: this.xLabel
              }
            }],
          }
        }
    }));
  }
}
