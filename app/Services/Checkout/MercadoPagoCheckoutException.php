<?php

namespace Inmuebles\Services\Checkout;

use Exception;

class MercadoPagoCheckoutException extends Exception
{

  public function __construct ($pago) {
    parent::__construct($pago);
  }

}