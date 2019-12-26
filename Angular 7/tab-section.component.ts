import { Component, OnInit,Input } from '@angular/core';
import { WeatherService } from '../weather.service';

@Component({
  selector: 'app-tab-section',
  templateUrl: './tab-section.component.html',
  styleUrls: ['./tab-section.component.css']
})
export class TabSectionComponent implements OnInit {
  @Input() tabCity:any;
  @Input() tabData:any;
  @Input() seal:any;
  @Input() addToFavoritesOption:any;
  @Input() favCityClicked:any;
  @Input() State:any;

  current = true;
  hourly = false;
  weekly =false;
  tweetMessage = "";


  // var tweetMessage = "https://twitter.com/intent/tweet?text="
  // +encodeURI("The current temperature at "+ this.city+ " is " +this.temperature+". The weather conditions are " +this.summary+" #CSCI571WeatherSearch");

  selectedTab = 'current';

  constructor( private weatherService: WeatherService ) { }

  ngOnInit() {
    if(this.tabData!=null){
    this.tweetMessage="https://twitter.com/intent/tweet?text="+encodeURIComponent("The current temperature at "+ this.tabCity+" is " +this.tabData.currently.temperature+"° F. The weather conditions are " +this.tabData.currently.summary+". \n#CSCI571WeatherSearch");
    }
  }

  onTabSelection(selectedTab){
    console.log('clicked on '+ selectedTab);
    this.selectedTab = selectedTab;
    if(selectedTab === 'current')
    {
      this.current =true;
      this.hourly =false;
      this.weekly =  false;
    }

    if(selectedTab === 'hourly')
    {
      this.current =false;
      this.hourly =true;
      this.weekly =  false;
    }

    if(selectedTab === 'weekly')
    {
      this.current =false;
      this.hourly =false;
      this.weekly =  true;
    }

  }


  favorites(){
    if(this.addToFavoritesOption){
    let favoriteInstance = {
      'city':this.tabCity,
      'state':this.State,
      'image':this.seal,
      'lat':this.tabData.latitude,
      'long':this.tabData.longitude
    };
    localStorage.setItem(this.tabCity+"@"+this.State, JSON.stringify(favoriteInstance));
    this.addToFavoritesOption = !this.addToFavoritesOption;
  }
  else
  {
    localStorage.removeItem(this.tabCity+"@"+this.State);
    this.addToFavoritesOption = !this.addToFavoritesOption;
  }
}

}
