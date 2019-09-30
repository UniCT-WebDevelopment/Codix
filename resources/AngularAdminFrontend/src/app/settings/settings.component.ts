import { Component, OnInit } from '@angular/core';
import { Option } from './resources/option';
import { SettingsService } from './settings.service';
import { ActivatedRoute } from '@angular/router';

@Component({
  selector: 'app-settings',
  templateUrl: './settings.component.html',
  styleUrls: ['./settings.component.css']
})
export class SettingsComponent implements OnInit {

  options_group: string;
  options: any;
  toggleVar: boolean = false;

  constructor(
    private settingsService: SettingsService,
    private activatedRoute: ActivatedRoute
    ) { }

  getData(): void {
    //this.settingsService.getOptions().subscribe(options => this.options = options);
    this.settingsService.getOption(this.options_group).subscribe( options => this.options = options);
  }



  isArray(obj: object): boolean {
    return Array.isArray(obj);
  }

  isString(str: any): boolean {
    return typeof str === 'string';
  }

  toggle(): void {
    this.toggleVar = !this.toggleVar;
  }

  ngOnInit() {
    this.activatedRoute.paramMap.subscribe(
      parms => {
        this.options_group = parms.get('group') ? parms.get('group') : '';
        this.getData();
      }
    )
    //this.getData();
  }

}
