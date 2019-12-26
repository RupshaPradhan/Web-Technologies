import { Component, OnInit } from '@angular/core';
import { WeatherService } from '../weather.service';
import { SealService } from '../seal.service';
import { AutoCompService } from '../auto-comp.service';
import { tick } from '@angular/core/src/render3';

@Component({
  selector: 'app-form',
  templateUrl: './form.component.html',
  styleUrls: ['./form.component.css']
})
export class FormComponent implements OnInit {

  //form data
  Street = "";
  City = '';
  State = '';
  isCurrent = false;
  states = [
    { 'st': { 'nm': 'AL', 'val': 'Alabama' } }, { 'st': { 'nm': 'AK', 'val': 'Alaska' } }, { 'st': { 'nm': 'AZ', 'val': 'Arizona' } }, { 'st': { 'nm': 'AR', 'val': 'Arkansas' } },
    { 'st': { 'nm': 'CA', 'val': 'California' } }, { 'st': { 'nm': 'CO', 'val': 'Colorado' } }, { 'st': { 'nm': 'CT', 'val': 'Connecticut' } }, { 'st': { 'nm': 'DE', 'val': 'Delaware' } },
    { 'st': { 'nm': 'DC', 'val': 'District of Columbia' } }, { 'st': { 'nm': 'FL', 'val': 'Florida' } }, { 'st': { 'nm': 'GA', 'val': 'Georgia' } }, { 'st': { 'nm': 'HI', 'val': 'Hawaii' } },
    { 'st': { 'nm': 'ID', 'val': 'Idaho' } }, { 'st': { 'nm': 'IL', 'val': 'Illinois' } }, { 'st': { 'nm': 'IN', 'val': 'Indiana' } }, { 'st': { 'nm': 'IO', 'val': 'Iowa' } }, { 'st': { 'nm': 'KS', 'val': 'Kansas' } }, { 'st': { 'nm': 'KY', 'val': 'Kentucky' } }, { 'st': { 'nm': 'LA', 'val': 'Louisiana' } },
    { 'st': { 'nm': 'ME', 'val': 'Maine' } }, { 'st': { 'nm': 'MD', 'val': 'Maryland' } }, { 'st': { 'nm': 'MA', 'val': 'Massachusetts' } }, { 'st': { 'nm': 'MI', 'val': 'Michigan' } }, { 'st': { 'nm': 'MN', 'val': 'Minnesota' } }, { 'st': { 'nm': 'MS', 'val': 'Mississippi' } }, { 'st': { 'nm': 'MO', 'val': 'Missouri' } },
    { 'st': { 'nm': 'MT', 'val': 'Montana' } }, { 'st': { 'nm': 'NE', 'val': 'Nebraska' } }, { 'st': { 'nm': 'NV', 'val': 'Nevada' } }, { 'st': { 'nm': 'NH', 'val': 'New Hampshire' } }, { 'st': { 'nm': 'NJ', 'val': 'New Jersey' } }, { 'st': { 'nm': 'NM', 'val': 'New Mexico' } }, { 'st': { 'nm': 'NY', 'val': 'New York' } },
    { 'st': { 'nm': 'NC', 'val': 'North Carolina' } }, { 'st': { 'nm': 'ND', 'val': 'North Dakota' } }, { 'st': { 'nm': 'OH', 'val': 'Ohio' } }, { 'st': { 'nm': 'OK', 'val': 'Oklahoma' } }, { 'st': { 'nm': 'OR', 'val': 'Oregon' } }, { 'st': { 'nm': 'PA', 'val': 'Pennsylvania' } }, { 'st': { 'nm': 'RI', 'val': 'Rhode Island' } },
    { 'st': { 'nm': 'SC', 'val': 'South Carolina' } }, { 'st': { 'nm': 'SD', 'val': 'South Dakota' } }, { 'st': { 'nm': 'TN', 'val': 'Tennessee' } }, { 'st': { 'nm': 'TX', 'val': 'Texas' } }, { 'st': { 'nm': 'UT', 'val': 'Utah' } }, { 'st': { 'nm': 'VT', 'val': 'Vermont' } }, { 'st': { 'nm': 'VA', 'val': 'Virginia' } },
    { 'st': { 'nm': 'WA', 'val': 'Washington' } }, { 'st': { 'nm': 'WV', 'val': 'West Virginia' } }, { 'st': { 'nm': 'WI', 'val': 'Wisconsin' } }, { 'st': { 'nm': 'WY', 'val': 'Wyoming' } }
  ];


  autocomplteData;
  lat;
  lon;
  seal;
  autoOptions;
  tabData: any;
  tabCity: any;
  tabState: any;
  sealData: any;
  localStorageObjects = [];
  addToFavoritesOption;
  favCityClicked: any;
  resultPageCity:any;
  resultPageState:any;


  //flags to decide display/hide features
  progressFlag;
  tabFlag = false;
  sError = false;
  cError = false;
  noRecordsFlag = false;
  favoritesTableFlag;
  ifFavTabClicked;


  constructor(private weatherService: WeatherService, private sealService: SealService, private autoCompService: AutoCompService) { }

  sFocus() {
    this.sError = false;
  }

  sBlur() {
    if (this.Street.trim() != '') {
      this.sError = false;
    }

    else {
      this.sError = true;
    }
  }

  cFocus() {
    this.cError = false;
  }

  cBlur() {
    if (this.City.trim() != '') {
      this.cError = false;
    }
    else {
      this.cError = true;
    }
  }


  ngOnInit() {
    // this.tabData =1
    this.progressFlag = false;
    this.tabFlag = false;
    this.favoritesTableFlag = false;
    this.ifFavTabClicked = false;
    this.noRecordsFlag = false;
    this.weatherService.apiData$.subscribe(data => {
    this.tabData = data;

    if(data!=null)
    {
      console.log('received weather details');
      this.sealService.getSeal(this.tabState);
    }



    });

    this.sealService.apiData2$.subscribe(data => {
      if(data!=null){
        console.log('received seal');
      this.seal = data;
      this.progressFlag = false;

      if (!this.favoritesTableFlag && this.ifFavTabClicked) {
          this.addToFavoritesOption = false;
          this.tabFlag = true;
      }
      else if (this.ifFavTabClicked) {
        this.tabFlag = false;
        this.favoritesTableFlag = true;
      }
      else {
        if (localStorage.getItem(this.tabCity)) {
          this.addToFavoritesOption = false;
        }
        else {
          this.addToFavoritesOption = true;
        }




        this.tabFlag = true;
        this.favoritesTableFlag = false;
      }
    }
    });

    this.autoCompService.apiData3$.subscribe(data => {
      let d = data;
      this.autoOptions = [];
      if (data != null) {
        let len = data["opt"].length;
        let i;
        for (i = 0; i < len; i++) {
          this.autoOptions.push(d["opt"][i]);
        }
      }
    });
  }


  onSearch() {
    if (this.City && this.Street && this.State || this.isCurrent) {
      this.favCityClicked = "";
      this.noRecordsFlag =false;
      this.progressFlag = true;
      this.tabFlag = false;
      this.favoritesTableFlag = false;
      this.ifFavTabClicked = false;
      if (this.isCurrent === true) {
        this.weatherService.extractLatLonWithCheckbox()
          .subscribe((data) => {
            this.lat = data['lat'];
            this.lon = data['lon'];
            this.resultPageCity = data['city'];
            this.resultPageState =data['region'];
            this.tabCity = data['city'];
            this.tabState = data['region'] ;
            this.weatherService.getWeatherCardDetails(this.lat, this.lon);
            console.log('received lat long');
        });
      }
      else {
        this.weatherService.extractLatLonWithUserProvidedAddress(this.Street.trim(), this.City.trim(), this.State.trim())
          .subscribe((data) => {
            if ((data['status']!= "OK") || (data['status']==[]) || (data['results'][0]['partial_match'])) {
              this.progressFlag = false;
              this.noRecordsFlag = true;
              this.tabData = null;
              return;
            }
            this.lat = data['results'][0]['geometry']['location']['lat'];
            this.lon = data['results'][0]['geometry']['location']['lng'];
            this.tabCity = this.City.trim();
            this.tabState = this.State.trim();

            this.weatherService.getWeatherCardDetails(this.lat, this.lon);

          });
      }
    }
  }

  onClear() {
    this.progressFlag = false;
    this.tabFlag = false;
    this.favoritesTableFlag = false;
    this.ifFavTabClicked = false;
    this.noRecordsFlag = false;
    this.sError = false;
    this.cError = false;
    this.Street = '';
    this.City = '';
    this.State = '';
    this.isCurrent = false;
    this.tabData=null;
  }

  onResultClick() {
    this.favoritesTableFlag = false;
    this.ifFavTabClicked = false;
    if((this.City=='' && this.isCurrent) || this.City)
    {
      if(( this.isCurrent && localStorage.getItem(this.resultPageCity+"@"+this.resultPageState)) || localStorage.getItem(this.tabCity+"@"+this.tabState)){
        this.addToFavoritesOption = false;
      }
      else{
        this.addToFavoritesOption = true;
      }

      // this.ifFavTabClicked = false;


      this.tabFlag = true;
      // this.favoritesTableFlag = false;
    }

  }

  onFavoritesClick() {
    this.noRecordsFlag =false;
    this.localStorageObjects = [];
    this.ifFavTabClicked = true;
    this.tabFlag = false;

    let keys = Object.keys(localStorage);
    let i = keys.length;

    while (i--) {
      this.localStorageObjects.push(JSON.parse(localStorage.getItem(keys[i])));
    }
    this.favoritesTableFlag = true;
  }

  messageFromChild(city) {
    this.favoritesTableFlag = false;
    this.progressFlag = true;
    this.favCityClicked = city;
    let clickedInstance = JSON.parse(localStorage.getItem(this.favCityClicked));
    let clickedLat = clickedInstance['lat'];
    let clickedLong = clickedInstance['long'];
    this.tabState = clickedInstance['state'];
    this.tabCity = clickedInstance['city'];

    this.weatherService.getWeatherCardDetails(clickedLat, clickedLong);
  }

  refreshWithUpdatedContents(msg) {
    this.favoritesTableFlag = false;
    this.onFavoritesClick();
  }

  onKey(value: String) {
    this.autocomplteData = false;
    this.autoCompService.getAutoCompOptions(value);
  }

}
