import { Component, OnInit, Input } from '@angular/core';
import { Resource } from 'src/app/library/resources/resource';
import { THIS_EXPR } from '@angular/compiler/src/output/output_ast';
import { DetailService } from '../detail/detail.service';

@Component({
  selector: 'app-box',
  templateUrl: './box.component.html',
  styleUrls: ['./box.component.css']
})
export class BoxComponent implements OnInit {
  showInterface: boolean = false;
  @Input() item: Resource;
  constructor(public detailService: DetailService) { }

  ngOnInit() {

  }

  over(){
    this.showInterface = !this.showInterface;
  }

}
