import { Component, OnInit, HostListener } from '@angular/core';
import { ReaderService } from './reader.service';
import { ActivatedRoute } from '@angular/router';
import { Location } from '@angular/common';

@Component({
  selector: 'app-reader',
  templateUrl: './reader.component.html',
  styleUrls: ['./reader.component.css']
})
export class ReaderComponent implements OnInit {
  fixed: number = 0;
  images: any;
  i = 0;
  constructor(
    private readerService: ReaderService,
    private activatedRoute: ActivatedRoute,
    private location: Location,
    ) { }

  getImages(id: string): void {
    this.readerService.getImages(id).subscribe(images => this.images = images);
  }

  close(): void {
    this.location.back();
  }

  next(): void {
    if (this.i < this.images.data.length) {
      this.i++;
    }
  }

  prev(): void {
    if (this.i > 0) {
      this.i--;
    }
  }

  adjust(): void {
    this.fixed = (this.fixed + 1 ) % 3;
  }

  @HostListener('window:keydown', ['$event'])
  onKeyDown(event) {
    switch (event.key) {
      case 'ArrowLeft':
        this.prev();
        break;
      case 'ArrowRight':
        this.next();
        break;
    }
  }

  ngOnInit() {
    this.activatedRoute.paramMap.subscribe(parms  => this.getImages(parms.get('id')));
    console.log(this.images.data);
  }

}
