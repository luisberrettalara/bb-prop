@extends('layouts.admin')
@section('content')
<div class="py-4">
  <div class="row">
    <div class="col-12">
      <div class="card">
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
        <div class="card-body">
          <div class="row">
            <div class="col-12">
                <h3>Opciones de la característica: {{$caracteristica->nombre}}</h3>
            </div>
          </div>
          <div class="row">
            <div class="col-12">
              <a class="btn btn-secondary" href="/admin/caracteristicas">Volver</a>
            </div>
          </div>
          <br />
          <div class="row">
            <div class="col-12">
              <form action="{{route('admin.caracteristicas.opciones.store', $caracteristica->id)}}" method="POST"> 
                <div class="input-group mb-3">
                    <input type="text" class="form-control{{ $errors->has('opcion') ? ' is-invalid' : '' }}" name="opcion" placeholder="Nombre">
                    @if ($errors->has('opcion'))
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $errors->first('opcion') }}</strong>
                        </span>
                    @endif
                    <div class="input-group-append">
                      <button class="btn btn-verde">Agregar</button>
                    </div>
                </div>
              </form>
            </div>
          </div>
          <br/>
          <div class="table-container table-responsive">
            <table class="table table-striped">
             <thead>
               <th>Opciones</th>
               <th width="150px">Acciones</th>
             </thead>
              <tbody>
                @if ($caracteristica->opciones->count())
                @foreach($caracteristica->opciones as $opcion)
                <tr>
                  <td>
                    {{$opcion->nombre}}
                  </td>
                  <td>
                      <form action="{{route('admin.caracteristicas.opciones.destroy', [$caracteristica->id, $opcion->id])}}" class="form-eliminar btn-group" method="post"> 
                        <a class="btn btn-primary btn-sm" title="Editar" href="{{route('admin.caracteristicas.opciones.edit', [$caracteristica->id, $opcion->id])}}">
                          <i class="fas fa-pencil-alt"></i>
                        </a>
                         @method('DELETE') 
                         <button class="btn btn-danger btn-sm" title="Eliminar" type="submit">
                          <i class="fas fa-trash-alt"></i>
                         </button>
                      </form>
                  </td>
                </tr>
                @endforeach
                @else
                 <tr>
                  <td colspan="2">No hay registro !!</td>
                </tr>
                @endif
              </tbody>
            </table>
          </div>
        </div> 
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