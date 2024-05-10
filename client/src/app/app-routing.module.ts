import { NgModule } from '@angular/core';
import { RouterModule, Routes } from '@angular/router';
import { IndexComponent } from './index/index.component';
import { BuscarVueloComponent } from './buscar-vuelo/buscar-vuelo.component';
import { RegistroComponent } from './registro/registro.component';
import { LoginCrudComponent } from './login-crud/login-crud.component';
import { FacturacionComponent } from './facturacion/facturacion.component';

const routes: Routes = [
  {
    path: '',
    component: IndexComponent,
  },
  {
    path: 'buscarVuelo',
    component: BuscarVueloComponent,
  },
  {
    path: 'registro',
    component: RegistroComponent,
  },
  {
    path: 'loginCrud',
    component: LoginCrudComponent,
  },
  {
    path: 'facturacion',
    component: FacturacionComponent,
  },
];

@NgModule({
  imports: [RouterModule.forRoot(routes)],
  exports: [RouterModule],
})
export class AppRoutingModule {}
