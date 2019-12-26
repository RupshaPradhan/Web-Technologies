import { Injectable } from '@angular/core';
import { HttpClient, HttpHeaders } from '@angular/common/http';
import { Data } from '@angular/router';
import { BehaviorSubject } from 'rxjs/internal/BehaviorSubject';

@Injectable({
  providedIn: 'root'
})
export class ModalService {
  private apiData = new BehaviorSubject<any>(null);
  public apiData5$ = this.apiData.asObservable();
  nodetest = '';

  constructor( private http: HttpClient ) { }

  getModalData(lat,long,time){
    this.nodetest = 'http://hw9backend.us-east-2.elasticbeanstalk.com';

    this.http.get(`${this.nodetest}/getModalData/?lat=${lat}&long=${long}&time=${time}`)
    .subscribe(
    ((data) => {
      console.log("modal");

      this.apiData.next(data);
    }));
  }
}

