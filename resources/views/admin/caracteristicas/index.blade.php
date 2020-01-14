@extends('layouts.admin')
@section('content')
<div class="py-4">

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

  <div class="row">
    <div class="col-12">
      <div class="card">
        <div class="card-body">
          <div class="row">
            <div class="col-md-6 col-12">
                <h3>Características</h3>
            </div>
            <div class="col-md-6 col-12 text-md-right">
                <a href="{{ route('admin.caracteristicas.create') }}" class="btn btn-verde">Añadir Característica</a>
            </div>
          </div>
          <br/>
          <div class="table-container table-responsive">
            <table class="table table-striped">
             <thead>
               <th>Nombre</th>
               <th>Es servicio</th>
               <th>Unidad</th>
               <th>Tipo Característica</th>
               <th width="150px">Acciones</th>
             </thead>
              <tbody>
                @if($caracteristica->count())
                @foreach($caracteristica as $car)
                <tr>
                  <td>{{$car->nombre}}</td>
                  <td>
                    @if($car->es_servicio=='1')
                      Si
                    @else
                      No
                    @endif
                  </td>
                  <td>{{$car->unidad}}</td>
                  <td>{{$car->tipoCaracteristica->nombre}}</td>
                  <td>
                    <form action="{{route('admin.caracteristicas.destroy', $car->id)}}" class="form-eliminar btn-group" method="post">
                      <a class="btn btn-primary btn-sm" title="Editar" href="{{route('admin.caracteristicas.edit', $car->id)}}">
                        <i class="fas fa-pencil-alt"></i>
                      </a>
                      @method('DELETE')
                      <button class="btn btn-danger btn-sm" title="Eliminar" type="submit">
                        <i class="fas fa-trash-alt"></i>
                      </button>
                      @if ($car->esDropdown())
                        <a class="btn btn-info btn-sm" title="Opciones" href="caracteristicas/{{$car->id}}/opciones">
                          <i class="fas fa-cog"></i> 
                        </a>
                      @endif
                    </form>
                  </td>
                </tr>
                @endforeach
                @else
                 <tr>
                  <td colspan="5">Aún no hay Características agregadas</td>
                </tr>
                @endif
              </tbody>
            </table>
          </div>
        </div>
      {{ $caracteristica->links('vendor.pagination.default') }}
      </div>
    </div>
  </div>
</div>
@endsection
@section('post-scripts')
<script type="text/javascript">
$(function() {
$('.form-eliminar').on('submit', function() {
   return confirm("¿Estás seguro que deseas eliminar ésta característica?");
}); 
});
</script>
@endsection