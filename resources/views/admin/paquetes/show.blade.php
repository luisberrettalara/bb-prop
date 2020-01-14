@extends('layouts.admin')
@section('content')
<div class="py-4">
  <div class="row justify-content-center">
    <div class="col-12">
      <div class="card">
        <div class="card-header">
          <h4>{{ $paquete }}</h4>
        </div>
        <div class="card-body">
          <p>Precio sin comisión: <strong>{{ $paquete->precio }}</strong></p>
          <p>Disponible: <strong>{{ $paquete->disponible ? 'Si' : 'No' }}</strong></p>

          <h3>Créditos incluidos</h3>
          <div class="table-container table-responsive">
            <table class="table table-striped">
             <thead>
               <th>Destacado</th>
               <th>Cantidad</th>
               <th>Días de Duración</th>
               <th>Días al Vencimiento</th>
             </thead>
              <tbody>
                @if($paquete->detalle()->count())
                @foreach($paquete->detalle as $detalle)
                <tr>
                  <td>{{ $detalle->tipoCredito->destacado ? 'Destacado' : 'Standard' }}</td>
                  <td>{{ $detalle->cantidad }}</td>
                  <td>{{ $detalle->tipoCredito->duracion_en_dias }}</td>
                  <td>{{ $detalle->tipoCredito->dias_al_vencimiento }}</td>
                </tr>
                @endforeach 
                @else
                 <tr>
                  <td colspan="4">Aún no hay registros disponibles</td>
                </tr>
                @endif
              </tbody>
            </table>
          </div>
        </div>

        <div class="card-footer">
          <div class="row">
            <div class="col-6">
              <a href="{{ route('admin.paquetes.index') }}" class="btn btn-info">
                Atrás
              </a>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
@endsection