import { Injectable } from '@angular/core';
import { HttpClient } from '@angular/common/http';
import { Observable } from 'rxjs';

@Injectable({
  providedIn: 'root'
})
export class ScannerService {

  constructor(private httpClient: HttpClient) { }

  getScanId(): Observable<any> {
    console.log('getScanId');
    return this.httpClient.get<any>('scanId');
  }

  scanJob(): Observable<any>{
    console.log('scanJob');
    return this.httpClient.get<any>('scanJob');
  }

  scanStatus(id: number): Observable<any> {
    console.log('request status of job: ' + id);
    return this.httpClient.get<any>('scanStatus/' + id);
  }

  scan(): Observable<any>{
    console.log('scan');
    return this.httpClient.get<any>('scan');
  }

}
