import { Component, OnInit } from '@angular/core';
import { SearchService } from './search.service';

@Component({
  selector: 'app-search',
  templateUrl: './search.component.html',
  styleUrls: ['./search.component.css']
})
export class SearchComponent implements OnInit {

  search(terms: string): void{
    this.searchService.search(terms);
  }

  constructor(public searchService: SearchService) { }

  ngOnInit() {
  }

}
