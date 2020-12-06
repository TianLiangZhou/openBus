import { NgModule } from '@angular/core';
import {SearchComponent} from "./search.component";
import {CoreModule} from "../../shared/core/core/core.module";
import {RouterModule, Routes} from "@angular/router";

const routes: Routes = [
  {path : '', component : SearchComponent}
];

@NgModule({
  declarations: [
    SearchComponent,
  ],
  imports: [
    CoreModule,
    RouterModule.forChild(routes),
  ],
  exports: [
    SearchComponent
  ],
})
export class SearchModule { }
