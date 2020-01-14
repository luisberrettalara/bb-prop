@extends('layouts.admin')
@section('content')
<div class="py-4">
  <div class="row">
    <div class="col-12">
      <div class="card">
        <div class="card-body">
          <div class="row">
            <div class="col-12">
                <h3>Historial de Estados para {{ $propiedad }}</h3>
                <p>Estado actual: <strong>{{ $propiedad->estado }}</strong></p>
            </div>
          </div>
          <br/>
          <div class="table-container">
            <table class="table table-striped">
             <thead>
               <th>Estado</th>
               <th>Fecha y hora</th>
               <th>Motivo</th>
             </thead>
              <tbody>
                @if($estados->count())
                @foreach($estados as $estado)
                <tr>
                  <td>{{ $estado->estado }}</td>
                  <td>{{ $estado->created_at->format('d/m/Y H:m') }}</td>
                  <td>{{ $estado->motivo }}</td>
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

          <div class="row">
            <div class="col-12">
              @if ($propiedad->sePuedePublicar())
                <a class="btn btn-outline-primary btn-sm" href="{{route('propiedades.publicar', $propiedad)}}">
                Publicar
                </a> 
              @endif

              @if ($propiedad->sePuedePausar())
              <a class="btn btn-outline-secondary btn-sm" href="{{route('propiedades.pausar', $propiedad)}}">
                Pausar
              </a>
              @endif

              @if ($propiedad->sePuedeReanudar())
              <a class="btn btn-outline-primary btn-sm" href="{{route('propiedades.reanudar', $propiedad)}}">
                Reanudar
              </a>
              @endif

              @if ($propiedad->sePuedeFinalizar())
              <a class="btn btn-outline-secondary btn-sm" href="{{route('propiedades.finalizar', $propiedad)}}">
                Finalizar
              </a>
              @endif

              @if ($propiedad->sePuedeDarDeBaja())
              <a class="btn btn-outline-danger btn-sm" href="{{ route('propiedades.baja', $propiedad) }}" >
                Dar de Baja
              </a>
              @endif
            </div>
          </div>
        </div>
      </div>

      <div class="text-center">
        {{ $estados->links('vendor.pagination.default') }}
      </div>
    </div>
  </div>
</div>
@endsection