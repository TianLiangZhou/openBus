import { NgModule } from '@angular/core';
import {HomepageComponent} from "./homepage.component";
import {CoreModule} from "../../shared/core/core/core.module";
import {RouterModule, Routes} from "@angular/router";
import {FormsModule} from "@angular/forms";

const routes: Routes = [
  {path : '', component : HomepageComponent}
];

@NgModule({
  declarations: [
    HomepageComponent,
  ],
  imports: [
    CoreModule,
    RouterModule.forChild(routes),
    FormsModule,
  ],
  exports: [
    HomepageComponent,
  ],
})
export class HomepageModule { }
