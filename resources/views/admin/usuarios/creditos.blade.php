@extends('layouts.admin')
@section('content')
<div class="py-4">
  <div class="row">
    <div class="col-12">
      @if(session('exito')) 
          <div class="alert alert-success" role="alert"> 
            {{session('exito')}} 
          </div>
        @endif
        @if(session('error')) 
          <div class="alert alert-danger" role="alert"> 
            {{session('error')}} 
          </div>
        @endif

      <div class="card">
        <div class="card-body">
          <div class="row">
            <div class="col-md-9 col-12">
                <h3>Crédito disponibles para {{ $usuario }}</h3>
            </div>
            <div class="col-md-3 col-12 text-md-right">
                <a href="{{ route('admin.paquetes.usuario.seleccionar', $usuario) }}" class="btn btn-verde">Asignar un Paquete</a>
            </div>
          </div>
          <br/>
          <div class="table-container table-responsive">
            <table class="table table-striped">
             <thead>
               <th>Días disponibles</th>
               <th>Días totales</th>
               <th>Destacado</th>
               <th>Vencimiento</th>
               <th>Propiedad</th>
             </thead>
              <tbody>
                @if($creditos->count())
                @foreach($creditos as $credito)
                <tr>
                  <td>{{ $credito->dias_disponibles }}</td>
                  <td>{{ $credito->dias_totales }}</td>
                  <td>{{ $credito->destacado ? 'Destacado' : 'Normal' }}</td>
                  <td>{{ $credito->fecha_vencimiento->format('d/m/Y') }}</td>
                  <td>
                    @if ($credito->tienePropiedad())
                    <a href="{{ route('propiedades.show', $credito->propiedad) }}">{{ $credito->propiedad }}</a>
                    @endif
                  </td>
                </tr>
                @endforeach 
                @else
                 <tr>
                  <td colspan="5">Aún no hay registros disponibles</td>
                </tr>
                @endif
              </tbody>
            </table>
          </div>
        </div>
      </div>

      <div class="text-center">
        {{ $creditos->links('vendor.pagination.default') }}
      </div>
    </div>
  </div>
</div>
@endsection