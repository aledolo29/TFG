import { Component, OnInit } from '@angular/core';
import { Title } from '@angular/platform-browser';

@Component({
  selector: 'app-buscar-vuelo',
  templateUrl: './buscar-vuelo.component.html',
  styleUrls: ['./buscar-vuelo.component.css'],
})
export class BuscarVueloComponent implements OnInit {
  constructor(private titleService: Title) {}

  ngOnInit(): void {
    this.titleService.setTitle('Buscar Vuelo');
  }
}
