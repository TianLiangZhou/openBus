import {ModuleWithProviders, NgModule} from '@angular/core';
import { CommonModule } from '@angular/common';
import {HttpClientModule} from "@angular/common/http";
import {AmapService} from "../../services/amap.service";



@NgModule({
  declarations: [

  ],
  imports: [
    CommonModule,
    HttpClientModule,
  ],
  exports: [
    CommonModule,
    HttpClientModule,
  ]
})
export class CoreModule {
  static forRoot(): ModuleWithProviders<CoreModule> {
    return {
      ngModule: CoreModule,
      providers: [
        { provide: AmapService, useClass: AmapService },
      ],
    };
  }
}
