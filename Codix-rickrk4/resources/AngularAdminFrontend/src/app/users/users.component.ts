import { Component, OnInit } from '@angular/core';
import { ResourceService } from '../resource.service';

@Component({
  selector: 'app-users',
  templateUrl: './users.component.html',
  styleUrls: ['./users.component.css']
})
export class UsersComponent implements OnInit {

  users: any;
  private url: string = 'user';

  constructor(private resourceService: ResourceService) { }

  getUsers(): void{
    this.resourceService.getData(this.url).subscribe(
      users => this.users = users
    )
  }

  registerUser(name: string, password: string, confirmPassword: string, email?: string): void {
    console.log("register user " + name + " password: " + password);
    this.resourceService.createData('user', {name: name, email: email, password: password, password_confirmation: confirmPassword}).subscribe();
  }

  ngOnInit() {
    this.getUsers();
  }

}
