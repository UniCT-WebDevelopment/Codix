import { Component, OnInit, Input, Output, EventEmitter } from '@angular/core';
import { CollectionService } from '../collection.service';
import { ResourceService } from 'src/app/resource.service';

@Component({
  selector: 'app-collection-detail',
  templateUrl: './collection-detail.component.html',
  styleUrls: ['./collection-detail.component.css']
})
export class CollectionDetailComponent implements OnInit {
  test: string;
  display: boolean = false;
  collection: any;

  @Input() id: number;
  @Input() name: string;
  @Input() trashed: boolean;
  @Output() refresh: EventEmitter<any> = new EventEmitter();

  constructor(private collectionService: CollectionService, private resourceSerivice: ResourceService) { }

  getCollection(): void{
    this.collectionService.getCollection(this.id).subscribe(
      collection => {
        this.collection = collection;
        this.name = this.collection.data.name;
        this.trashed = this.collection.trashed;
      }
    );
  }

  delete(): void{
    this.collectionService.deleteCollection(this.id).subscribe(
      test => this.test = test
    );
    this.getCollection();
  }

  destroy(): void{
    this.collectionService.destroyCollection(this.id).subscribe(
      test => this.test = test
    );
    //TODO Generate item destroy signal
    this.refresh.emit(this.id);
  }

  restore(): void{
    this.collectionService.restoreCollection(this.id).subscribe(
      test => this.test = test
    );
    this.getCollection();
  }

  attachData(resource: any): void {
    this.resourceSerivice.updateData('cl', this.id, {attach: [resource]}).subscribe(
      () => this.getCollection()
    );
  }

  detachData(resource: any): void {
    this.resourceSerivice.updateData('cl', this.id, {detach: [resource]}).subscribe(
      () => this.getCollection()
    )
  }

  updateData(body: any): void{
    this.resourceSerivice.updateData('cl', this.id, {toUpdate: body}).subscribe(
      () => this.getCollection()
    )
  }

  updateCollection(title: string): void{

    this.collectionService.updateData(this.id, {
     name: title
    }).subscribe(
    test => this.test = test
    );
    this.getCollection();

    this.display = !this.display;
  }

  detach( comics: string, series: string): void{
    this.collectionService.updateData(this.id, {
      detach: {
        comics: [comics],
        series: [series]
      }
    }).subscribe(
    test => this.test = test
    );
    this.getCollection();
  }

  addComic(comics: string, series: string): void {
    this.collectionService.updateData(this.id, {
        attach: {
          comics: [comics],
          series: [series]
        }
      }).subscribe(
      test => this.test = test
    );
    this.getCollection();
  }


  toggle(): boolean{
    if(this.collection === null) {
      this.getCollection();
    }
    return (this.display = !this.display);
  }

  ngOnInit() {
    this.getCollection();
  }

}
