import { Injectable } from '@angular/core';
import { HttpClient, HttpHeaders } from '@angular/common/http';
import { Data } from '@angular/router';
import { BehaviorSubject } from 'rxjs/internal/BehaviorSubject';

@Injectable({
  providedIn: 'root'
})
export class WeatherService {
  private apiData = new BehaviorSubject<any>(null);
  public apiData$ = this.apiData.asObservable();


   nodetest = '';
  // state = 'CA';
  // city = 'Los Angeles';
  //street = '1172 West 30th Street';

  constructor(private http: HttpClient) { }

  extractLatLonWithCheckbox() {
    console.log('Checkbox ticked----extracting lat lon');
    this.nodetest = 'http://ip-api.com/json/';
    return this.http.get(`${this.nodetest}`);
  }

  extractLatLonWithUserProvidedAddress(street, city, state){

    console.log('With user street and city----extracting lat lon');
    this.nodetest = 'http://hw9backend.us-east-2.elasticbeanstalk.com';

    console.log(`${this.nodetest}/extractLatLonWithUserProvidedAddress/?street=${street}&city=${city}&state=${state}`);
    return this.http.get(`${this.nodetest}/extractLatLonWithUserProvidedAddress/?street=${street}&city=${city}&state=${state}`);
  }

  getWeatherCardDetails(lat,lon){
    this.nodetest = 'http://hw9backend.us-east-2.elasticbeanstalk.com';

    this.http.get(`${this.nodetest}/extractWeatherDetails/?lat=${lat}&lon=${lon}`)
    .subscribe(
    ((data) => {
      console.log("weather");

      this.apiData.next(data);
    }));
  }




}
