import { Component, OnInit } from '@angular/core';
import { LibraryService } from './library.service';
import { ActivatedRoute } from '@angular/router';
import { Observable } from 'rxjs';
import { Resource } from './resources/resource';
import { Resources } from '../resources.enum';
import { SearchService } from '../search/search.service';
import {debounceTime, distinctUntilChanged, switchMap} from 'rxjs/operators';
import { DetailService } from '../gallery/detail/detail.service';
import { resource } from 'selenium-webdriver/http';
@Component({
  selector: 'app-library',
  templateUrl: './library.component.html',
  styleUrls: ['./library.component.css']
})
export class LibraryComponent implements OnInit {
  resource: any = Resources;
  resources: any;
  resourcesSearched: any;
  resource$: any;
  test: any;
  constructor(
    private librarySerivce: LibraryService,
    private activatedRoute: ActivatedRoute,
    private searchService: SearchService,
    public detailService: DetailService,
  ) { }

  type: string;
  id: string;

  getData(resource: string = null, id: string = null): void {
    console.log("request: " + resource + id);
    this.librarySerivce.getData( resource, id).subscribe(
      resources => this.resources = resources//resources.data
    );
  }

  changePage(url: string){
    console.log('change to: ' + url);
    this.librarySerivce.get(url).subscribe(
      resources => this.resources = resources
    );
  }

  nextPage(): void{
    this.librarySerivce.get(this.resources.links.next).subscribe(
      resources => this.test = resources
    )
  }

  prevPage(): void{
    this.librarySerivce.get(this.resources.links.prev).subscribe(
      resources => this.test = resources
    )
  }
/*
  changePage(url: string): void {
    this.librarySerivce.get(url).subscribe(
      resources => this.resources = resources
    );
  }
*/
  show(): boolean{
    return true;
  }

  isSearching(){
    return this.searchService.isSearching();
  }

  advancedSearch(artist_id: number): void{

  }

  ngOnInit() {
    this.activatedRoute.paramMap.subscribe(parms => {
      this.type = parms.get('type') ? parms.get('type') : 'default';
      this.id = parms.get('id') ? '/' + parms.get('id') : '';
      this.test = Resources[this.type] + this.id;

      // Da eseguire solo in build
      this.getData(this.type, this.id);

      this.searchService.searchTerms.pipe(
        debounceTime(400),
        distinctUntilChanged(),
        switchMap((term: string) => this.librarySerivce.search(term))
        //switchMap((term: string) => this.librarySerivce.advancedSearch({terms: term}))
      ).subscribe(resources => this.resource$ = resources);
    });

    this.test = this.type + '/' + this.id;
  }

}
