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
            <div class="col-6">
                <h3>Paquetes</h3>
            </div>
            <div class="col-6 text-right">
                <a href="{{ route('admin.paquetes.create') }}" class="btn btn-verde">Nuevo Paquete</a>
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
               <th>Por defecto</th>
               <th>Precio</th>
               <th width="150px">Acciones</th>
             </thead>
              <tbody>
                @forelse($paquetes as $paquete)
                <tr>
                  <td></td>
                  <td>{{ $paquete }}</td>
                  <td>{{ $paquete->creditosStandard() }}</td>
                  <td>{{ $paquete->creditosDestacados() }}</td>
                  <td>
                    <a href="{{ route('admin.paquetes.disponibilizar', $paquete) }}">{{ $paquete->disponible ? 'Si' : 'No' }}</a>
                  </td>
                  <td>{{$paquete->por_defecto? 'Si' : 'No'}}</td>
                  <td>${{ $paquete->precio }}</td>
                  <td>
                    <form action="{{route('admin.paquetes.destroy', $paquete->id)}}" class="form-eliminar btn-group" method="post">
                      <a class="btn btn-primary btn-sm" title="Ver" href="{{route('admin.paquetes.show', $paquete->id)}}">
                        <i class="fas fa-eye"></i>
                      </a>
                      <a class="btn btn-primary btn-sm" title="Editar" href="{{route('admin.paquetes.edit', $paquete->id)}}">
                        <i class="fas fa-pencil-alt"></i>
                      </a>
                       @method('DELETE') 
                       <button class="btn btn-danger btn-sm" title="Eliminar" type="submit">
                        <i class="fas fa-trash-alt"></i>
                       </button>
                    </form>
                  </td>
                </tr>
                @empty
                 <tr>
                  <td colspan="8">Aún no hay registros disponibles</td>
                </tr>
                @endforelse
              </tbody>
            </table>
          </div>
        </div>
      </div>

      <div class="text-center">
        {{ $paquetes->links('vendor.pagination.default') }}
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