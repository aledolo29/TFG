import { ComponentFixture, TestBed } from '@angular/core/testing';

import { PagarVueloComponent } from './pagar-vuelo.component';

describe('PagarVueloComponent', () => {
  let component: PagarVueloComponent;
  let fixture: ComponentFixture<PagarVueloComponent>;

  beforeEach(async () => {
    await TestBed.configureTestingModule({
      declarations: [PagarVueloComponent]
    })
    .compileComponents();
    
    fixture = TestBed.createComponent(PagarVueloComponent);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});
