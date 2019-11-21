import { Injectable } from '@angular/core';
import { HttpClient } from '@angular/common/http';
import { Observable, of } from 'rxjs';
import { Resources } from './resources.enum';

@Injectable({
  providedIn: 'root'
})
export class AppService {


  private heroesUrl = 'c';  // URL to web api
  constructor(private httpClient: HttpClient) { }

  getData(resource: string, id: string): Observable<any>{

    return this.httpClient.get<any>(this.heroesUrl);
  }

}
