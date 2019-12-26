import { Component, OnInit,Input,Output,EventEmitter } from '@angular/core';
import { FavoriteService } from '../favorite.service';

@Component({
  selector: 'app-favorites',
  templateUrl: './favorites.component.html',
  styleUrls: ['./favorites.component.css']
})
export class FavoritesComponent implements OnInit {
  @Input() tableData : any;
  @Output() valueChange = new EventEmitter();
  progressFlag;
  detailedFavFlag;
  showOuterTableFlag =true;
  @Output() refreshFavTable = new EventEmitter();


  favContent : any;
  constructor( private favoriteService: FavoriteService ) { }

  ngOnInit() {

  }

  SearchForFavoriteCity(city,state){
    console.log(city);
    this.valueChange.emit(city+"@"+state);
  }

  removeselectedFavorite(city,state)
  {
    localStorage.removeItem(city+"@"+state);
    this.refreshFavTable.emit('refresh');
  }
}
