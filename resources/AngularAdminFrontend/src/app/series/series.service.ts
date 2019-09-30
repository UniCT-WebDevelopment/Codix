import { Injectable } from '@angular/core';
import { HttpClient } from '@angular/common/http';
import { Observable, of } from 'rxjs';

@Injectable({
  providedIn: 'root'
})
export class SeriesService {

  constructor( private httpClient: HttpClient) { }

  searchSeries(terms: string): Observable<any> {
    if (!terms.trim || terms === "") {
      return of([]);
    }
    return this.httpClient.get<any>('s/?q=' + terms);
  }

}
