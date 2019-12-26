import { Injectable } from '@angular/core';
import { HttpClient, HttpHeaders } from '@angular/common/http';
import { Data } from '@angular/router';
import { BehaviorSubject } from 'rxjs/internal/BehaviorSubject';

@Injectable({
  providedIn: 'root'
})
export class SealService {
  private apiData = new BehaviorSubject<any>(null);
  public apiData2$ = this.apiData.asObservable();
  nodetest = '';

  constructor( private http: HttpClient ) { }

  getSeal(state){
    this.nodetest = 'http://hw9backend.us-east-2.elasticbeanstalk.com';

    this.http.get(`${this.nodetest}/getSeal/?state=${state}`)
    .subscribe(
    ((data) => {
      console.log("seal");

      this.apiData.next(data);
    }));
  }
}
