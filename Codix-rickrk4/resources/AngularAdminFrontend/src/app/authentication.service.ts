import { Injectable } from '@angular/core';
import { HttpClient, HttpHeaders } from '@angular/common/http';
import { Router } from '@angular/router';
import { Meta } from '@angular/platform-browser';

@Injectable({
  providedIn: 'root'
})
export class AuthenticationService {
  private token: string;
  private httpOptions: any;

  constructor(private httpClient: HttpClient, private router: Router, private meta: Meta) { }

  login(): void {
    console.log('token: ' + (this.token = this.meta.getTag('name="csrf-token"').content));
    this.httpOptions = {
      headers: new HttpHeaders({
        'X-CSRF-TOKEN': this.token
      })
    };
  }

  logout(): void {
    console.log('logout');
    if (this.token != null) {
      this.httpClient.post<any>('logout', {}, this.httpOptions).subscribe();
    }
  }

  getToken(): string {
    return this.token;
  }

  getHttpOptions(): any{
    return this.httpOptions;
  }
}
