import { Component, OnInit } from '@angular/core';
import { ScannerService } from '../scanner.service';
import { Observable, interval } from 'rxjs';
import { SettingsService } from '../settings/settings.service';

@Component({
  selector: 'app-home-admin',
  templateUrl: './home-admin.component.html',
  styleUrls: ['./home-admin.component.css']
})
export class HomeAdminComponent implements OnInit {

  scanJob: any;
  scanning: boolean = false;
  scanJobId: number;

  private scanStatus: any = interval(1000);

  constructor(private scannerService: ScannerService, private settingsService: SettingsService) { }

  getScanId(): void {
    this.scannerService.getScanId().subscribe(
      scanJobId => {
        this.scanJobId = scanJobId;
        // tslint:disable-next-line: curly
        if (this.scanJobId != 0)
          this.startScan();
      }
    )
  }

  getScanStatus(): void {
    this.scannerService.scanStatus(this.scanJobId).subscribe(
      scanJob => {
        this.scanJob = scanJob;
        if (scanJob.status === 'finished') {
          this.endScan();
        }
      }
    )
  }

  startScan(): void {
    if ( this.scanJobId === 0 ) {
      this.scanning = true;
      this.scannerService.scan().subscribe(
        scanJobId => this.scanJobId = scanJobId
      );
      this.scanStatus.subscribe(() => this.getScanStatus());
    }
  }

  endScan(): void {
    this.scanning = false;
    this.scanJob = null;
    this.scanJobId = 0;
    this.scanStatus.unsubscribe();
  }




  ngOnInit() {
    this.getScanId();
    interval(1000).subscribe(() => this.getScanStatus());
  }

}
