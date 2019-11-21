import { Injectable } from '@angular/core';
import { HttpClient, HttpHeaders } from '@angular/common/http';
import { Observable, of } from 'rxjs';
import { AuthenticationService } from '../authentication.service';
import { COLLECTIONS } from '../tests/mocks/mock-collections';

@Injectable({
  providedIn: 'root'
})
export class CollectionService {

  private httpOptions = {
    header: new HttpHeaders({
      'X-CSRF-TOKEN': this.authService.getToken()
    })
  }

  constructor(private httpClient: HttpClient, private authService: AuthenticationService) { }

  getData(): Observable<any> {
    return of(COLLECTIONS);
    return this.httpClient.get<any>('cl');
  }

  getCollection(id: number): Observable<any> {
    return this.httpClient.get<any>('cl/' + id + '/edit');
  }

  createData(body: any): Observable<any> {
    return this.httpClient.post<any>('cl', body);
  }

  updateData(id: number, body: any): Observable<any> {
    return this.httpClient.put<any>('cl/' + id, body);
  }

  addComic(id: number, body): string {
    this.httpClient.put<any>('/cl/comic/' + id, body);
    return 'arrivato';
  }

  deleteCollection(id: number): Observable<any> {
    return this.httpClient.delete<any>('cl/' + id);
  }

  restoreCollection(id: number): Observable<any> {
    return this.httpClient.get<any>('cl/restore/' + id);
  }

  destroyCollection(id: number): Observable<any> {
    return this.httpClient.delete<any>('cl/destroy/' + id);
  }

  searchComics(terms: string): Observable<any> {
    if (!terms.trim || terms === "") {
      return of([]);
    }
    return this.httpClient.get<any>('c/?q=' + terms);
  }

}
