import { Component, OnInit } from '@angular/core';
import { AuthenticationService } from './authentication.service';
import { Meta } from '@angular/platform-browser';

@Component({
  selector: 'app-root',
  templateUrl: './app.component.html',
  styleUrls: ['./app.component.css']
})
export class AppComponent implements OnInit {
  title = 'AngularAdminFrontend';

  constructor(private authService: AuthenticationService, private meta: Meta){}

  ngOnInit() {
    //console.log( 'token: ' + this.meta.getTag('name="csrf-token"').content );
    this.authService.login();
  }
}
