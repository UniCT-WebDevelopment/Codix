import { Component, OnInit, Input } from '@angular/core';
import { ResourceService } from 'src/app/resource.service';

@Component({
  selector: 'app-user-detail',
  templateUrl: './user-detail.component.html',
  styleUrls: ['./user-detail.component.css']
})
export class UserDetailComponent implements OnInit {

  user: any;
  @Input() id: number;
  constructor(private resourceService: ResourceService) { }

  getUser(): void {
    this.resourceService.getData('user', this.id).subscribe(
      user => this.user = user
    )
    console.log(this.user.data.rules);
  }

  addRule(type: string, name: string, allow: any): void {

    this.resourceService.createData('rule', {rules: [{ userId: this.id, type, name, allow }]}).subscribe( () => this.getUser() );
    return;
    this.resourceService.updateData('add/rule', this.user.data.id, {rules: [{type, name, allow}]}).subscribe(
      () => this.getUser()
    )
  }

  deleteRule(rule_id: number, type: string, id: string): void {

    this.resourceService.deleteData('rule', rule_id).subscribe( () => this.getUser() );
    return
    this.resourceService.updateData('delete/rule', this.user.data.id, {rules: [{type, id}]}).subscribe(
      () => this.getUser()
    )
  }

  ngOnInit() {
    this.getUser();
  }

}
