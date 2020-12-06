import { NgModule } from '@angular/core';
import { SearchRoutingModule } from './search-routing.module';
import {SearchComponent} from "./search.component";
import {CoreModule} from "../../shared/core/core/core.module";


@NgModule({
  declarations: [
    SearchComponent,
  ],
  imports: [
    CoreModule,
    SearchRoutingModule,
  ],
  exports: [
    SearchComponent
  ],
})
export class SearchModule { }
