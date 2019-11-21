import { BrowserModule } from '@angular/platform-browser';
import { NgModule } from '@angular/core';
import { HttpClient, HttpClientModule } from '@angular/common/http';

import { AppRoutingModule } from './app-routing.module';
import { AppComponent } from './app.component';
import { HeaderComponent } from './header/header.component';
import { RouterComponent } from './header/router/router.component';
import { SettingsComponent } from './settings/settings.component';
import { OptionComponent } from './settings/option/option.component';
import { ToggleComponent } from './toggle/toggle.component';
import { CollectionComponent } from './collection/collection.component';
import { CollectionDetailComponent } from './collection/collection-detail/collection-detail.component';
import { ComicComponent } from './comic/comic.component';
import { ComicDetailComponent } from './comic/comic-detail/comic-detail.component';
import { SearchComponent } from './collection/search/search.component';
import { SeriesComponent } from './series/series.component';
import { FooterComponent } from './footer/footer.component';
import { HomeAdminComponent } from './home-admin/home-admin.component';
import { UsersComponent } from './users/users.component';
import { UserDetailComponent } from './users/user-detail/user-detail.component';

@NgModule({
  declarations: [
    AppComponent,
    HeaderComponent,
    RouterComponent,
    SettingsComponent,
    OptionComponent,
    ToggleComponent,
    CollectionComponent,
    CollectionDetailComponent,
    ComicComponent,
    ComicDetailComponent,
    SearchComponent,
    SeriesComponent,
    FooterComponent,
    HomeAdminComponent,
    UsersComponent,
    UserDetailComponent
  ],
  imports: [
    BrowserModule,
    AppRoutingModule,
    HttpClientModule
  ],
  providers: [],
  bootstrap: [AppComponent]
})
export class AppModule { }
