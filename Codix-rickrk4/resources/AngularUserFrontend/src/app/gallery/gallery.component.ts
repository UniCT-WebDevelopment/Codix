import { Component, OnInit, Input } from '@angular/core';
import { Resource } from '../library/resources/resource';
import { Observable } from 'rxjs';
import { DetailService } from './detail/detail.service';

@Component({
  selector: 'app-gallery',
  templateUrl: './gallery.component.html',
  styleUrls: ['./gallery.component.css']
})
export class GalleryComponent implements OnInit {
  @Input() item$: Observable<Resource[]>;
  @Input() items: Resource[];

  constructor(public detailService: DetailService) { }

  ngOnInit() {
  }

}
