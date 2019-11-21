import { Component, OnInit, Input, Output, EventEmitter } from '@angular/core';

@Component({
  selector: 'app-footer',
  templateUrl: './footer.component.html',
  styleUrls: ['./footer.component.css']
})
export class FooterComponent implements OnInit {
  @Input() links: any;
  @Input() meta: any;
  @Output() change: EventEmitter<any> = new EventEmitter();
  @Input() current: number;
  @Input() first: number = 1;
  @Input() last: number;
  @Output() next: EventEmitter<any> = new EventEmitter();
  @Output() prev: EventEmitter<any> = new EventEmitter();

  constructor() { }

  ngOnInit() {  }

  changePage(url: string): void {
    this.change.emit(url);
  }

  nextPage(): void{
    console.log(this.links.next);
    this.change.emit(this.links.next);
  }

  prevPage(): void{
    console.log(this.links.prev);
    this.change.emit(this.links.prev);
  }

  pageFirst(): void {
    console.log(this.links.first);
    this.change.emit(this.links.first);
  }

  pageLast(): void {
    console.log(this.links.last);
    this.change.emit(this.links.last);
  }

}
