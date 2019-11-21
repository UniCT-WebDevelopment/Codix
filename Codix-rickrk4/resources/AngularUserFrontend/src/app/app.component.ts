import { Component } from '@angular/core';
import { AppService } from './app.service';
import { ActivatedRoute } from '@angular/router';
import { DetailService } from './gallery/detail/detail.service';
import { AuthenticationService } from './authentication.service';


@Component({
  selector: 'app-root',
  templateUrl: './app.component.html',
  styleUrls: ['./app.component.css']
})
export class AppComponent {
  title = 'AngularUserFrontend';
  data: any;
  id: string;
  type: string;
  test: string;
  constructor(
    private service: AppService,
    private activatedRoute: ActivatedRoute,
    public detailService: DetailService,
    private authenticationService: AuthenticationService,
    ) { }

  getData() {
    this.service.getData(this.type, this.id).subscribe(data => this.data = data.data);
  }

  ngOnInit() {
//    console.log('token: ' +  $('meta[name="csrf-token"]').attr('content') );
    this.authenticationService.login();
    this.activatedRoute.paramMap.subscribe(
      p => {
        this.id = p.get('id');
        this.type = p.get('resource');
      });
    this.getData();
  }
}
