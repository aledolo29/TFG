import { NgModule } from '@angular/core';
import { BrowserModule } from '@angular/platform-browser';

import { AppRoutingModule } from './app-routing.module';
import { AppComponent } from './app.component';
import { IndexComponent } from './index/index.component';
import { BuscarVueloComponent } from './buscar-vuelo/buscar-vuelo.component';
import { PagarVueloComponent } from './pagar-vuelo/pagar-vuelo.component';
import { LoginCrudComponent } from './login-crud/login-crud.component';
import { RegistroComponent } from './registro/registro.component';

@NgModule({
  declarations: [
    AppComponent,
    IndexComponent,
    BuscarVueloComponent,
    PagarVueloComponent,
    LoginCrudComponent,
    RegistroComponent
  ],
  imports: [
    BrowserModule,
    AppRoutingModule
  ],
  providers: [],
  bootstrap: [AppComponent]
})
export class AppModule { }
