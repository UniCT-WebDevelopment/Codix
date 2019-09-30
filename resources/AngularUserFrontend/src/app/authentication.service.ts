import { Injectable } from '@angular/core';
import { HttpClient } from '@angular/common/http';
import { Meta } from '@angular/platform-browser';

@Injectable({
  providedIn: 'root'
})
export class AuthenticationService {

  logged: boolean = false;
  private token: string;

  constructor(private httpClient: HttpClient, private meta: Meta) { }

  logout(): void {
    if(this.logged)
      this.httpClient.get<any>('logout');
  }

  login(): void {
    console.log('token: ' + (this.token = this.meta.getTag('name="csrf-token"').content));
    /**Log in */
    // this.httpClient.get<any>('users/getToken').subscribe(  token => this.token = token  );
    this.logged = true;
  }

  getToekn(): string {
    return this.token;
  }

}
