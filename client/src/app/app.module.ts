import { NgModule } from '@angular/core';
import { BrowserModule } from '@angular/platform-browser';
import { HttpClientModule } from '@angular/common/http';
import { FormsModule } from '@angular/forms';

import { AppRoutingModule } from './app-routing.module';
import { AppComponent } from './app.component';
import { IndexComponent } from './index/index.component';
import { BuscarVueloComponent } from './buscar-vuelo/buscar-vuelo.component';
import { LoginCrudComponent } from './login-crud/login-crud.component';
import { RegistroComponent } from './registro/registro.component';
import { FacturacionComponent } from './facturacion/facturacion.component';

@NgModule({
  declarations: [
    AppComponent,
    IndexComponent,
    BuscarVueloComponent,
    LoginCrudComponent,
    RegistroComponent,
    FacturacionComponent,
  ],
  imports: [BrowserModule, AppRoutingModule, HttpClientModule, FormsModule],
  providers: [],
  bootstrap: [AppComponent],
})
export class AppModule {}
