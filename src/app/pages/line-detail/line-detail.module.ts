import { NgModule } from '@angular/core';
import {CoreModule} from "../../shared/core/core/core.module";
import {LineDetailComponent} from "./line-detail.component";
import {RouterModule, Routes} from "@angular/router";

const routes: Routes = [
  {path : '', component : LineDetailComponent}
];


@NgModule({
  declarations: [
    LineDetailComponent,
  ],
  imports: [
    CoreModule,
    RouterModule.forChild(routes),
  ],
  exports: [
    LineDetailComponent
  ]
})
export class LineDetailModule { }
