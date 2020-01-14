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
          <form method="POST" action="{{ route('admin.paquetes.usuario.asignar', $usuario) }}">
            <div class="row">
              <div class="col-12">
                  <h3>Asignar un Paquete a un Anunciante</h3>
                  <p>Usuario: <strong>{{ $usuario }}</strong></p>
              </div>
            </div>
            <br/>
            <div class="table-container table-responsive">
              <table class="table table-striped">
               <thead>
                 <th></th>
                 <th>Nombre</th>
                 <th>Créditos Standard</th>
                 <th>Créditos Destacados</th>
                 <th>Disponible</th>
                 <th>Precio</th>
               </thead>
                <tbody>
                  @if($paquetes->count())
                  @foreach($paquetes as $paquete)
                  <tr>
                    <td>
                      <div class="form-check">
                        <input class="form-check-input" type="checkbox" value="{{ $paquete->id }}" name="paquetes[]">
                      </div>
                    </td>
                    <td>{{ $paquete }}</td>
                    <td>{{ $paquete->creditosStandard() }}</td>
                    <td>{{ $paquete->creditosDestacados() }}</td>
                    <td>
                      <a href="{{ route('admin.paquetes.disponibilizar', $paquete) }}">{{ $paquete->disponible ? 'Si' : 'No' }}</a>
                    </td>
                    <td>${{ $paquete->precio }}</td>
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

            @if ($errors->any())
            <div class="alert alert-danger">
              <ul>
                @foreach ($errors->all() as $error)
                  <li>{{ $error }}</li>
                @endforeach
              </ul>
            </div>
            @endif

            <div class="row">
              <div class="col-12 text-right">
                  <button class="btn btn-primary">Asignar los Paquetes seleccionados</button>
              </div>
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>
</div>
@endsection