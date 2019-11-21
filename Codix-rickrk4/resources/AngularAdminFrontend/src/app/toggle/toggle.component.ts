import { Component, OnInit, Input, Output, EventEmitter } from '@angular/core';

@Component({
  selector: 'app-toggle',
  templateUrl: './toggle.component.html',
  styleUrls: ['./toggle.component.css']
})
export class ToggleComponent implements OnInit {

  @Input() value: boolean = false;
  @Input() disable: boolean = false;
  @Output() toggle: EventEmitter<any> = new EventEmitter();
  constructor() { }

  ngOnInit() { }

  changeState(): void {
    this.toggle.emit(this.value = !this.value);
  }

}
