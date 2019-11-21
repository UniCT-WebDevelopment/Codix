import { Component, OnInit, Input } from '@angular/core';
import { Option } from '../resources/option';
import { HttpClient, HttpHeaders } from '@angular/common/http';

@Component({
  selector: 'app-option',
  templateUrl: './option.component.html',
  styleUrls: ['./option.component.css']
})
export class OptionComponent implements OnInit {


  value: any;
  @Input() option: Option;
  @Input() url: string = '';
  @Input() crsf_token: string;
  constructor(private httpClient: HttpClient) { }

  ngOnInit() {
    this.value = this.option.value;
  }

  update(value: any) {
    this.option.value = value;
  }

  modified(): boolean {
    return this.value !== this.option.value;
  }

  isString(str: any): boolean{
    return typeof str === 'string';
  }

  send(): void {
    //TODO Send data

    // tslint:disable-next-line: max-line-length
    console.log('update: ' + this.url);
    this.httpClient.put<any>('admin/' + (this.url != '' ? (this.url + '.') : '') + this.option.name + (this.url != '' ? '.value' : ''), {
      option: this.option.name,
      value: this.option.value,
      _token: this.crsf_token}).subscribe(value => this.value = value);

  }

}
