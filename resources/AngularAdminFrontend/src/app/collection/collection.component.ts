import { Component, OnInit } from '@angular/core';
import { CollectionService } from './collection.service';

@Component({
  selector: 'app-collection',
  templateUrl: './collection.component.html',
  styleUrls: ['./collection.component.css']
})
export class CollectionComponent implements OnInit {
  test: string;
  collections: any;
  constructor(private collectionService: CollectionService) { }

  createCollection(value: string): void{
    this.collectionService.createData({title: value}).subscribe(
      test => this.test = test
    );
    this.getCollections();
  }

  updateCollection(id: number, body: any): void{
    this.collectionService.updateData(id, body);
  }

  getCollections(): void{
    this.collectionService.getData().subscribe(
      collections => this.collections = collections
    );
  }

  ngOnInit() {
    this.getCollections();
   }

}
