<?php

namespace Inmuebles\Services\Checkout;

use Illuminate\Support\Collection;

use Inmuebles\Models\Paquetes\Paquete;
use Inmuebles\Models\Usuarios\User;

interface CheckoutService {

  public function checkout (Paquete $paquete, User $usuario);

}
