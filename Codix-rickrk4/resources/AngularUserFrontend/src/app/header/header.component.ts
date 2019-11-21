import { Component, OnInit } from '@angular/core';
import { Location } from '@angular/common';
import { AuthenticationService } from '../authentication.service';

@Component({
  selector: 'app-header',
  templateUrl: './header.component.html',
  styleUrls: ['./header.component.css']
})
export class HeaderComponent implements OnInit {

  constructor(private location: Location, public authenticationService: AuthenticationService) { }

  back(): void {
    this.location.back();
  }

  ngOnInit() {
  }

}
