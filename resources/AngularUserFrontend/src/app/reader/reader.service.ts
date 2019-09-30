import { Injectable } from '@angular/core';
import { HttpClient } from '@angular/common/http';
import { Observable } from 'rxjs';

@Injectable({
  providedIn: 'root'
})
export class ReaderService {

  // tslint:disable-next-line: no-inferrable-types
  private apiUrl: string = 'c';

  constructor(
    private httpClient: HttpClient
  ) { }


  getImages(id: string): Observable<any>{
    return this.httpClient.get<any>(this.apiUrl + '/' + id);
  }
}
