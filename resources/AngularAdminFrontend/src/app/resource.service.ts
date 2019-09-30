import { Injectable } from '@angular/core';
import { HttpClient, HttpHeaders } from '@angular/common/http';
import { Observable, of } from 'rxjs';
import { AuthenticationService } from './authentication.service';
import { COMICS } from './tests/mocks/mock-comics';
import { COLLECTIONS } from './tests/mocks/mock-collections';

@Injectable({
  providedIn: 'root'
})
export class ResourceService {

  private httpOptions = {
    headers: new HttpHeaders({
      'X-CSRF-TOKEN': this.authService.getToken()
    })
  }

  constructor(private httpClient: HttpClient, private authService: AuthenticationService) { }

  getData(url: string, id?: number, query?: string): Observable<any> {
    console.log(url + '/' + (id ? id : '') + '?' + (query ? query : ''));

    return this.httpClient.get<any>(url + '/' + (id ? id : '') + '?' + (query ? query : ''));
  }

  createData(url: string, body: any): Observable<any> {
    console.log('create new entry on ' + url);
    return this.httpClient.post<any>(url, body, this.httpOptions);
  }

  editData(url: string, id: number): Observable<any> {
    console.log('call to ' + url + '/' + id + '/edit');
    return this.httpClient.get<any>(url + '/' + id + '/edit');
  }

  updateData(url: string, id: number, body: any): Observable<any> {
    console.log('update of ' + url + '/' + id );
    return this.httpClient.put<any>(url + '/' + id, body, this.authService.getHttpOptions());
  }

  deleteData(url: string, id: number): Observable<any> {
    console.log('delete of ' + url + '/' + id );
    return this.httpClient.delete<any>(url + '/' + id, this.httpOptions);
  }

  restoreData(url: string, id: number): Observable<any> {
    console.log('restore of ' + url + '/' + id );
    return this.httpClient.get<any>(url + '/' + id + '/restore' );
  }

  destroyData(url: string, id: number): Observable<any> {
    console.log('destroy of ' + url + '/' + id + '/destroy');
    return this.httpClient.delete<any>(url + '/' + id + '/destroy' );
  }

}
