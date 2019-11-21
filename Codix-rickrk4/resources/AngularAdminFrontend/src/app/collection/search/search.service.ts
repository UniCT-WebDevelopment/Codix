import { Injectable } from '@angular/core';
import { HttpClient } from '@angular/common/http';
import { Observable, of } from 'rxjs';

@Injectable({
  providedIn: 'root'
})
export class SearchService {
  searching: boolean = false;
  constructor(private httpClient: HttpClient) { }

  search(url: string, terms: string): Observable<any> {
    if (!terms.trim || terms === "") {
      this.searching = false;
      return of([]);
    }
    this.searching = true;
    console.log("search: " + url + '/?q=' + terms);
    return this.httpClient.get<any>(url + '/?q=' + terms);
  }

}
