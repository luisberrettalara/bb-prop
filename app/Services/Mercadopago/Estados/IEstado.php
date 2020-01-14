<?php

namespace Inmuebles\Services\Mercadopago\Estados;

use Inmuebles\Models\Pedidos\Pedido;

interface IEstado {

	public function procesar (Pedido $pedido, $paymentInfo);
}
