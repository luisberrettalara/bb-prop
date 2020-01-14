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
            <div class="col-md-6 col-12">
                <h3>Tipos de Crédito</h3>
            </div>
            <div class="col-md-6 col-12 text-left text-md-right">
                <a href="{{ route('admin.tipos-credito.create') }}" class="btn btn-verde">Nuevo Tipo de Crédito</a>
            </div>
          </div>
          <br/>
          <div class="table-container table-responsive">
            <table class="table table-striped">
             <thead>
               <th>Días de Duración</th>
               <th>Destacado</th>
               <th>Días al Vencimiento</th>
               <th width="150px">Acciones</th>
             </thead>
              <tbody>
                @if($tiposCredito->count())
                @foreach($tiposCredito as $tipo)
                <tr>
                  <td>{{ $tipo->duracion_en_dias }}</td>
                  <td>{{ $tipo->destacado ? 'Destacado' : 'Standard' }}</td>
                  <td>{{ $tipo->dias_al_vencimiento }}</td>
                  <td>
                    <form action="{{route('admin.tipos-credito.destroy', $tipo->id)}}" class="form-eliminar btn-group" method="post">
                      <a class="btn btn-primary btn-sm" title="Editar" href="{{route('admin.tipos-credito.edit', $tipo->id)}}">
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
                  <td colspan="4">Aún no hay registros disponibles</td>
                </tr>
                @endif
              </tbody>
            </table>
          </div>
        </div>
      </div>

      <div class="text-center">
        {{ $tiposCredito->links('vendor.pagination.default') }}
      </div>
    </div>
  </div>
</div>
@endsection
@section('post-scripts')
<script type="text/javascript">
$(function() {
$('.form-eliminar').on('submit', function() {
   return confirm("¿Estás seguro que deseas eliminar este registro?");
}); 
});
</script>
@endsection