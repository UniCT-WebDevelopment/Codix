import { Component, OnInit, Input, Output, EventEmitter } from '@angular/core';
import { ComicService } from '../comic.service';
import { ResourceService } from 'src/app/resource.service';

@Component({
  selector: 'app-comic-detail',
  templateUrl: './comic-detail.component.html',
  styleUrls: ['./comic-detail.component.css']
})
export class ComicDetailComponent implements OnInit {

  display: boolean = false;

  @Input() comic: any;
  @Output() refresh: EventEmitter<any> = new EventEmitter();
  constructor(private comicService: ComicService, private resourceService: ResourceService) { }

  ngOnInit() { }

  getComic(): void {
    /*
    this.comicService.getComic(this.comic.id).subscribe(
      comic => this.comic = comic.data
    );
    */

   this.resourceService.editData('c', this.comic.id).subscribe(
     comic => this.comic = comic.data
   )
  }

  attach(body: any): void {
    console.log("attach " + body.publishers);
    // this.comicService.updateComic(this.comic.id, {attach: body}).subscribe(() => console.log("attached"));
    this.resourceService.updateData('c', this.comic.id, {attach: body}).subscribe();
    this.getComic();
  }

  detach(body: any): void {
    this.resourceService.updateData('c', this.comic.id, {detach: body}).subscribe();
    this.getComic();
  }
/*
  attachAuthor(author: string): void {
    this.comicService.updateComic(this.comic.id, {
      attach: {
        authors: [author]
      }
    }).subscribe();
    this.getComic();
  }

  detachAuthor(author: string): void{
    this.comicService.updateComic(this.comic.id, {
      detach: {
        authors: [author]
      }
    }).subscribe();
    this.getComic();
  }
*/
  update(obj: any): void{
    this.resourceService.updateData('c', this.comic.id, {toUpdate: obj}).subscribe();
    //this.comicService.updateComic(this.comic.id, {toUpdate: obj}).subscribe();
    this.getComic();
  }

  toggle(): void{
    if(!this.display){
      this.getComic();
    }
    this.display = !this.display;
  }

  delete(): void{
    this.comicService.deleteComic(this.comic.id).subscribe();
    this.getComic();

  }

  restore():void{
    this.comicService.restoreComic(this.comic.id).subscribe();
    this.getComic();
    this.refresh.emit();
  }

  forceDelete(): void{
    this.comicService.forceDeleteComic(this.comic.id).subscribe();
    this.refresh.emit();
  }

}
