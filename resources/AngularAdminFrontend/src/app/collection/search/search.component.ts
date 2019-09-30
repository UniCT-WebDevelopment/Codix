import { Component, OnInit, Output, EventEmitter, Input } from '@angular/core';
import { ComicService } from 'src/app/comic/comic.service';

import { debounceTime, distinctUntilChanged, switchMap } from 'rxjs/operators';
import { Observable, Subject } from 'rxjs';
import { CollectionService } from '../collection.service';
import { SearchService } from './search.service';
import { ResourceService } from 'src/app/resource.service';

@Component({
  selector: 'app-search',
  templateUrl: './search.component.html',
  styleUrls: ['./search.component.css']
})
export class SearchComponent implements OnInit {
  searching: boolean = false;
  resources$: any;
  @Input() url: string;
  @Input() placeholder: string;
  @Input() insert: boolean = true;
  @Output() selected: EventEmitter<any> = new EventEmitter();
  terms: string;
  private searchTerms: Subject<string> = new Subject<string>();

  constructor(private comicService: SearchService, private resourceService: ResourceService) { }

  search(terms: string): void {
    if (terms === '') {
      this.searching = false;
      this.resources$ = null;
    } else {
      this.searchTerms.next(terms);
      this.terms = terms;
    }
  }

  select(id: number) {
    this.search('');
    this.selected.emit(id);
  }



  ngOnInit() {

    this.searchTerms.pipe(
      debounceTime(300),
      distinctUntilChanged(),
      // switchMap((term: string) => this.comicService.search(this.url, term))
      switchMap((term: string) => this.resourceService.getData(this.url, null, 'q=' + term))
      ).subscribe(
      comics => this.resources$ = comics
    );
/*
    this.comicService.searchComics('').subscribe(
      test => this.test = test
    )
*/
  }

}
