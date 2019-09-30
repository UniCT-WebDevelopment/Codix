import { Injectable } from '@angular/core';
import { Observable, of } from 'rxjs';
import { HttpClient } from '@angular/common/http';
import { COMICS, COMIC } from '../tests/mocks/mock-comics';

@Injectable({
  providedIn: 'root'
})
export class ComicService {

  constructor(private httpClient: HttpClient) { }

  getData(): Observable<any>{
    return of(COMICS);
    return this.httpClient.get<any>('c');
  }

  getComic(id: number): Observable<any>{
    return of(COMIC);
    return this.httpClient.get<any>('c/' + id + '/edit');
  }

  updateComic(id: number, body: any): Observable<any>{
    return this.httpClient.put<any>('c/'+id, body);
  }

  deleteComic(id: number): Observable<any>{
    return this.httpClient.delete<any>('c/'+id);
  }

  restoreComic(id: number): Observable<any>{
    return this.httpClient.get<any>('c/restore/'+id);
  }

  forceDeleteComic(id: number): Observable<any>{
    return this.httpClient.delete<any>('c/destroy/'+id);
  }

  searchComics(terms: string): Observable<any> {
    if (!terms.trim || terms === "") {
      return of([]);
    }
    return this.httpClient.get<any>('c/?q=' + terms);
  }

}
