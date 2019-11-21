import { Injectable } from '@angular/core';
import { Resource } from 'src/app/library/resources/resource';
import { LibraryService } from 'src/app/library/library.service';

@Injectable({
  providedIn: 'root'
})
export class DetailService {

  private showBool: boolean = false;
  resource: any;

  constructor(private libraryService: LibraryService) { }

  showDetail(resource: Resource): void {
    console.log('detail of:' + resource.id);

    this.libraryService.getDetail(resource.type, resource.id ).subscribe(
      detail => {
        console.log(detail.data.title);
        this.resource = detail.data;
        this.showBool = true;
      }
    );

    // this.resource = resource;

  }

  show(): boolean{
    return this.showBool;
  }

  close(): void{
    this.showBool = false;
  }

}
