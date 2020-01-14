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
                <h3>Usos</h3>
            </div>
            <div class="col-6 text-right">
                <a href="{{ route('admin.usos.create') }}" class="btn btn-verde">Añadir nuevo Uso</a>
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
                @if($uso->count())
                @foreach($uso as $us)
                <tr>
                  <td>{{$us->nombre}}</td>
                  <td>
                    <form action="{{route('admin.usos.destroy', $us->id)}}" class="form-eliminar btn-group" method="post">
                      <a class="btn btn-primary btn-sm" title="Editar" href="{{route('admin.usos.edit', $us->id)}}">
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
      {{ $uso->links('vendor.pagination.default') }}
      </div>
    </div>
  </div>
</div>
@endsection
@section('post-scripts')
<script type="text/javascript">
$(function() {
$('.form-eliminar').on('submit', function() {
   return confirm("¿Estás seguro que deseas eliminar éste uso?");
}); 
});
</script>
@endsection