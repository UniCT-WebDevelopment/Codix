import { NgModule } from '@angular/core';
import { Routes, RouterModule } from '@angular/router';
import { AppComponent } from './app.component';
import { LibraryComponent } from './library/library.component';
import { TestComponent } from './test/test.component';
import { ReaderComponent } from './reader/reader.component';


const routes: Routes = [
  { path: 'library', redirectTo: 'library/d/', pathMatch: 'full'},
  { path: 'library/reader/:id', component: ReaderComponent },
  { path: 'library/c/:id', component: ReaderComponent },
  { path: 'library/:type', component: LibraryComponent},
  { path: 'library/:type/:id', component: LibraryComponent },
  { path: 'test/:type/:id', component: TestComponent }
];

@NgModule({
  imports: [RouterModule.forRoot(routes)],
  exports: [RouterModule]
})
export class AppRoutingModule { }
