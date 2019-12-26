import { Component, OnInit,Input } from '@angular/core';
import { WeatherService } from '../weather.service';

@Component({
  selector: 'app-current',
  templateUrl: './current.component.html',
  styleUrls: ['./current.component.css']
})
export class CurrentComponent implements OnInit {
  weatherCard: any;
  @Input() currentData:any;
  @Input() seal:any;
  @Input() tabCity:any;
  humidity:any;
  pressure:any;
  windSpeed:any;
  visibility:any;
  cloudCover:any;
  ozone:any;
  timezone:any;
  temperature:any;
  summary:any;

  constructor(  private weatherService: WeatherService ) { }

  ngOnInit() {
    console.log('Inside current'+this.seal);
    this.humidity=Math.round(this.currentData.currently.humidity*100)/100;
    this.pressure=Math.round(this.currentData.currently.pressure*100)/100;
    this.windSpeed=Math.round(this.currentData.currently.windSpeed*100)/100;
    this.visibility=Math.round(this.currentData.currently.visibility*100)/100;
    this.cloudCover=Math.round(this.currentData.currently.cloudCover*100)/100;
    this.ozone=Math.round(this.currentData.currently.ozone*100)/100;
    this.timezone=this.currentData.timezone;
    this.temperature=Math.round(this.currentData.currently.temperature);
    this.summary=this.currentData.currently.summary;
  }

}
