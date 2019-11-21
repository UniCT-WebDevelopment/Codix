import { Injectable } from '@angular/core';
import { Subject } from 'rxjs';

@Injectable({
  providedIn: 'root'
})
export class SearchService {
  public searchTerms = new Subject<string>();
  public advancedSearchTerms = new Subject<any>();
  private searching: boolean = false;
  constructor() { }

  search(terms: string): void{
    this.searchTerms.next(terms);
    this.searching = terms != 'q=' && terms != 'Cerca Fumetti';
    console.log(terms);
  }

  isSearching(): boolean{
    return this.searching;
  }

}
