<?php

namespace Inmuebles\Services\Mercadopago;

use Exception;

class TopicoInexistenteException extends Exception {
  
  public function __construct ($topic) {
    parent::__construct ("El tópico '$topic' no está soportado");
  }

}
