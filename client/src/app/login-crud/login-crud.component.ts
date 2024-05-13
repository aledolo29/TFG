import { Component } from '@angular/core';
import { HttpClient } from '@angular/common/http';
import { environment } from '../../environments/environment';
@Component({
  selector: 'app-login-crud',
  templateUrl: './login-crud.component.html',
  styleUrls: ['./login-crud.component.css'],
})
export class LoginCrudComponent {
  empl_Usuario = '';
  empl_Password = '';

  constructor(private http: HttpClient) {}

  comprobarLogin() {
    const body = {
      empl_Usuario: this.empl_Usuario,
      empl_Password: this.empl_Password,
    };
    this.http
      .post(`${environment.apiUrl}/comprobarEmpleado`, body)
      .subscribe((response) => {});
  }
}
