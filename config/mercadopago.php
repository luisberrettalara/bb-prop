<?php

return [
  'client_id'     => env('MP_CLIENT_ID', ''),
  'client_secret' => env('MP_CLIENT_SECRET', ''),
  'sandbox'       => env('MP_SANDBOX', false),
  'comision'      => env('MP_COMISION', 0.06),
  'estados'       => [
    'approved'     => 'aprobado', 
    'pending'      => 'pendiente-de-acreditacion', 
    'in_process'   => 'procesando', 
    'rejected'     => 'rechazado', 
    'refunded'     => 'reintegrado', 
    'cancelled'    => 'cancelado',
    'in_mediation' => 'disputa'
  ]
];