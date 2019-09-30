import { BrowserModule } from '@angular/platform-browser';
import { NgModule } from '@angular/core';
import { HttpClient, HttpClientModule } from '@angular/common/http';


import { AppRoutingModule } from './app-routing.module';
import { AppComponent } from './app.component';
import { LibraryComponent } from './library/library.component';
//import { HederComponent } from './heder/heder.component';
import { HeaderComponent } from './header/header.component';
import { TestComponent } from './test/test.component';
import { RouterComponent } from './header/router/router.component';
import { ReaderComponent } from './reader/reader.component';
import { GalleryComponent } from './gallery/gallery.component';
import { BoxComponent } from './gallery/box/box.component';
import { SearchComponent } from './search/search.component';
import { DetailComponent } from './gallery/detail/detail.component';
import { FooterComponent } from './footer/footer.component';



@NgModule({
  declarations: [
    AppComponent,
    LibraryComponent,
//    HederComponent,
    HeaderComponent,
TestComponent,
RouterComponent,
ReaderComponent,
GalleryComponent,
BoxComponent,
SearchComponent,
DetailComponent,
FooterComponent
  ],
  imports: [
    BrowserModule,
    AppRoutingModule,
    HttpClientModule,
  ],
  providers: [],
  bootstrap: [AppComponent]
})
export class AppModule { }
