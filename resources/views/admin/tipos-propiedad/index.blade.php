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
            <div class="col-6">
                <h3>Tipos de Propiedades</h3>
            </div>
            <div class="col-6 text-right">
                <a href="{{ route('admin.tipos-propiedad.create') }}" class="btn btn-verde">Añadir Nuevo tipo</a>
            </div>
          </div>
          <br/>
          <div class="table-container table-responsive">
            <table class="table table-striped">
             <thead>
               <th>Nombre</th>
               <th width="150px">Acciones</th>
             </thead>
              <tbody>
                @if($tipos->count())
                @foreach($tipos as $tip)
                <tr>
                  <td>{{$tip->nombre}}</td>
                  <td>
                    <form action="{{route('admin.tipos-propiedad.destroy', $tip->id)}}" class="form-eliminar btn-group" method="post">
                      <a class="btn btn-primary btn-sm" title="Editar" href="{{route('admin.tipos-propiedad.edit', $tip->id)}}">
                        <i class="fas fa-pencil-alt"></i>
                      </a>
                       @method('DELETE')
                       <button class="btn btn-danger btn-sm" title="Eliminar" type="submit">
                        <i class="fas fa-trash-alt"></i>
                       </button>
                      <a class="btn btn-info btn-sm" title="Características" href="tipos-propiedad/{{$tip->id}}/caracteristicas">
                        <i class="fas fa-cog"></i>
                      </a>
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
      {{ $tipos->links('vendor.pagination.default') }}
      </div>
    </div>
  </div>
</div>
@endsection
@section('post-scripts')
<script type="text/javascript">
$(function() {
$('.form-eliminar').on('submit', function() {
   return confirm("¿Estás seguro que deseas eliminar éste tipo de propiedad?");
});
});
</script>
@endsection
