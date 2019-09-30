import { Component, OnInit, HostListener } from '@angular/core';
import { ActivatedRoute } from '@angular/router';
import { ResourceMock } from '../library/test/resource-mock';
import { LibraryService } from '../library/library.service';
import { Resource } from '../library/resources/resource';

@Component({
  selector: 'app-test',
  templateUrl: './test.component.html',
  styleUrls: ['./test.component.css']
})
export class TestComponent implements OnInit {
  type: string;
  items: Resource[];
  test: any;
  id: string;
  private resourceMock: ResourceMock = new ResourceMock();
  constructor(
    private activatedRoute: ActivatedRoute,
    private libraryService: LibraryService
  ) { }

  ngOnInit() {
    this.libraryService.getData(null, null).subscribe(
      items => this.items = items
    );
    // this.test = this.resourceMock.getDir();

  }

  @HostListener('window:keydown', ['$event'])
    onKeyDown(event) {
      this.type = event.keyCode;
    }

}
