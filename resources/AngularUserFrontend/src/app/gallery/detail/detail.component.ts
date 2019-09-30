import { Component, OnInit, Output, EventEmitter } from '@angular/core';
import { DetailService } from './detail.service';
import { SearchService } from 'src/app/search/search.service';

@Component({
  selector: 'app-detail',
  templateUrl: './detail.component.html',
  styleUrls: ['./detail.component.css']
})
export class DetailComponent implements OnInit {

  constructor(public detailService: DetailService, public searchService: SearchService) { }

  close(): void {
    this.detailService.close();
  }

  isEmpty(vett: any[]): boolean {
    return vett == null || vett.length === 0;
  }



  ngOnInit() { }

}
