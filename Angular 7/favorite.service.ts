import { Injectable } from '@angular/core';
import { HttpClient, HttpHeaders } from '@angular/common/http';
import { Data } from '@angular/router';
import { BehaviorSubject } from 'rxjs/internal/BehaviorSubject';


@Injectable({
  providedIn: 'root'
})
export class FavoriteService {
  nodetest = '';
  private apiData = new BehaviorSubject<any>(null);
  public apiData2$ = this.apiData.asObservable();

  constructor( private http: HttpClient ) { }

  getFavCardDetails(lat,lon){
    this.nodetest = 'http://hw9backend.us-east-2.elasticbeanstalk.com';
    this.http.get(`${this.nodetest}/extractWeatherDetails/?lat=${lat}&lon=${lon}`)
    .subscribe(
    ((data) => {
      console.log("favs");

      this.apiData.next(data);
    }));
  }

}
