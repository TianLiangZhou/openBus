import { NgModule } from '@angular/core';
import { Routes, RouterModule } from '@angular/router';


const routes: Routes = [
  {
    path: '', pathMatch: 'full',
    loadChildren: () => import('./pages/homepage/homepage.module').then(m => m.HomepageModule)
  },
  {
    path: 'line/:id',
    loadChildren: () => import('./pages/line-detail/line-detail.module').then(m => m.LineDetailModule)
  },

  {path: '**', redirectTo: ''},
];

@NgModule({
  imports: [RouterModule.forRoot(routes, {
    relativeLinkResolution: 'legacy',
    // scrollPositionRestoration: 'enable',
  })],
  exports: [RouterModule]
})
export class AppRoutingModule { }
