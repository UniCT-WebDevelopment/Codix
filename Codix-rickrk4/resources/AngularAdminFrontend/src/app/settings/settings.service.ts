import { Injectable } from '@angular/core';
import { Option } from './resources/option';
import { Observable, of } from 'rxjs';
import { SETTINGS } from '../tests/mocks/mock-settings';
import { HttpClient } from '@angular/common/http';

@Injectable({
  providedIn: 'root'
})
export class SettingsService {

  private url = 'setting';

  constructor(private httpClient: HttpClient) { }

  getOptions(): Observable<any> {
    console.log('request all options');
      // Solo in build
    return of(SETTINGS);
    return this.httpClient.get<any>('admin');

  }
  getOption(opt: string): Observable<any> {
    console.log('request option: ' + opt);
    return this.httpClient.get<any>('admin/' + opt);
  }

  setOption(opt: string, newValue: any): Observable<any> {
    console.log('edit option: ' + opt);
    return this.httpClient.put<any>('admin/' + opt, {
      value: newValue
    });
  }

}
