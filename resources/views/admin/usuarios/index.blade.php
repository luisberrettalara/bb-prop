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
            <div class="col-5">
                <h3>Usuarios</h3>
            </div>
            <div class="col-7 text-right">
                <a href="{{route('admin.usuarios.create')}}" class="btn btn-verde">Añadir nuevo usuario</a>
            </div>
          </div>
          <br/>
          <div class="table-container table-responsive">
            <table class="table table-striped">
             <thead>
               <th>Razon social</th>
               <th>Email</th>
               <th>Dirección</th>
               <th>Teléfono</th>
               <th>Rol</th>
               <th width="150px">Acciones</th>
             </thead>
              <tbody>
                @if($usuario->count())
                @foreach($usuario as $usu)
                <tr>
                  <td>{{$usu->razon_social}}</td>
                  <td>{{$usu->email}}</td>
                  <td>{{$usu->direccion}}</td>
                  <td>{{$usu->telefono}}</td>
                  <td>{{$usu->rol->nombre}}</td>
                  <td>
                    <form action="{{route('admin.usuarios.destroy', $usu->id)}}" class="form-eliminar btn-group" method="post">
                      <a class="btn btn-primary btn-sm" title="Editar" href="{{route('admin.usuarios.edit', $usu->id)}}">
                        <i class="fas fa-pencil-alt"></i>
                      </a>
                      @if(!$usu->noEsAnunciante())
                        <a class="btn btn-outline-secondary btn-sm" title="Créditos" href="{{route('admin.usuarios.creditos', $usu->id)}}">
                          <i class="fas fa-list-ul"></i>
                        </a>
                      @endif
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
                  <td colspan="4">No hay registro !!</td>
                </tr>
                @endif
              </tbody>
            </table>
          </div>
        </div>
      {{ $usuario->links('vendor.pagination.default') }}
      </div>
    </div>
  </div>
</div>
@endsection
@section('post-scripts')
<script type="text/javascript">
$(function() {
$('.form-eliminar').on('submit', function() {
   return confirm("¿Estás seguro que deseas eliminar éste usuario?");
}); 
});
</script>
@endsection