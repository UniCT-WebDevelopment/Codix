import { Component, OnInit } from '@angular/core';
import { ComicService } from './comic.service';
import { ResourceService } from '../resource.service';
import { COMICS } from '../tests/mocks/mock-comics';

@Component({
  selector: 'app-comic',
  templateUrl: './comic.component.html',
  styleUrls: ['./comic.component.css']
})
export class ComicComponent implements OnInit {

  comics: any;

  getComics(): void {

   this.comics = {data: [
    {url: 'c\/1',
     title: 'Asterix & i Goti',
     coverUrl: 'g\/1',
     type: 'c',
     id: 1,
     trashed: false},
   ]};
   this.comics = COMICS;
    // Solo in build
   // this.resourceService.getData('c', null, '').subscribe(  comics => this.comics = comics );
  }

  constructor(private comicService: ComicService, private resourceService: ResourceService) { }

  ngOnInit() {
    this.getComics();
  }

}
