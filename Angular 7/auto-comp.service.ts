import { Injectable } from '@angular/core';
import { HttpClient, HttpHeaders } from '@angular/common/http';
import { Data } from '@angular/router';
import { BehaviorSubject } from 'rxjs/internal/BehaviorSubject';


@Injectable({
  providedIn: 'root'
})
export class AutoCompService {
  private apiData = new BehaviorSubject<any>(null);
  public apiData3$ = this.apiData.asObservable();
  nodetest = '';

  constructor( private http: HttpClient ) { }

  getAutoCompOptions(value){
    this.nodetest = 'http://hw9backend.us-east-2.elasticbeanstalk.com';
    console.log(value);
    this.http.get(`${this.nodetest}/getAutoCompOptions/?enteredValue=${value}`)
    .subscribe(
    ((data) => {
      console.log("auto");
      this.apiData.next(data);
    }));
  }


}
