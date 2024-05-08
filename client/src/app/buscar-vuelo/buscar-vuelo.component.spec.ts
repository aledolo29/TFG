import { ComponentFixture, TestBed } from '@angular/core/testing';

import { BuscarVueloComponent } from './buscar-vuelo.component';

describe('BuscarVueloComponent', () => {
  let component: BuscarVueloComponent;
  let fixture: ComponentFixture<BuscarVueloComponent>;

  beforeEach(async () => {
    await TestBed.configureTestingModule({
      declarations: [BuscarVueloComponent]
    })
    .compileComponents();
    
    fixture = TestBed.createComponent(BuscarVueloComponent);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});
