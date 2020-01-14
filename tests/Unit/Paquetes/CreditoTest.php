<?php

namespace Tests\Unit\Paquetes;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

use Inmuebles\Models\Paquetes\Credito;
use Inmuebles\Models\Propiedades\Propiedad;

use Carbon\Carbon;

class CreditoTest extends TestCase
{

  public function testCreditoNoDisponible() {
    $credito = new Credito(3, 365, true);

    $credito->disminuirDiasDisponibles();
    $credito->disminuirDiasDisponibles();
    $credito->disminuirDiasDisponibles();

    $this->assertFalse($credito->estaDisponible());
  }

  public function testCreditoDisponible() {
    $credito = new Credito(30, 365, true);

    $credito->disminuirDiasDisponibles();
    $credito->disminuirDiasDisponibles();
    $credito->disminuirDiasDisponibles();

    $this->assertTrue($credito->estaDisponible());
  }

  public function testCreditoVencido() {
    Carbon::setTestNow(Carbon::create(2018, 1, 1)); 
    $credito = new Credito(30, 365, true);

    Carbon::setTestNow(Carbon::create(2019, 1, 2)); 

    $this->assertTrue($credito->estaVencido());
  }

  public function testCreditoNoVencidoElDiaDelVencimiento() {
    Carbon::setTestNow(Carbon::create(2018, 1, 1)); 
    $credito = new Credito(30, 365, true);

    Carbon::setTestNow(Carbon::create(2019, 1, 1)); 

    $this->assertFalse($credito->estaVencido());
  }

  public function testCreditoLibre() {
    $credito = new Credito(30, 365, true);

    $this->assertTrue($credito->estaLibre());
  }

}
