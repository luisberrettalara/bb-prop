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
                <h3>Características del tipo de Propiedad: {{$tipo_propiedad->nombre}}</h3>
            </div>
          </div>
          <div class="row">
            <div class="col-12">
              <a class="btn btn-secondary" href="/admin/tipos-propiedad">Volver</a>
            </div>
          </div>
          <br />
          <div class="row">
            <div class="col-12">
              <form action="{{route('admin.tipos-propiedad.caracteristicas.store', $tipo_propiedad->id)}}" method="POST"> 
                <div class="input-group mb-3">
                    <input type="text" id="caracteristica-autocompletar" class="form-control" placeholder="Nombre" autofocus="autofocus">
                    <input type="hidden" name="caracteristica" id="caracteristica-id">
                    <div class="input-group-append">
                      <button class="btn btn-verde">Agregar</button>
                    </div>
                </div>
              </form>
            </div>
          </div>
          <br/>
          <div class="table-container">
            <table class="table table-striped">
             <thead>
              <th></th>
              <th>Características</th>
              <th width="150px">Acciones</th>
             </thead>
              <tbody id="sortable">
                @if($tipo_propiedad->caracteristicas->count())
                @foreach($tipo_propiedad->caracteristicas as $caracteristica)
                <tr data-url="{{route('admin.tipos-propiedad.caracteristicas.reordenar', [$tipo_propiedad->id, $caracteristica->id])}}">
                  <td><i class="fas fa-sort fa-2x fila-ordenable-handle" title="Ordenar"></i></td>
                  <td>
                    {{$caracteristica->nombre}}
                  </td>
                  <td>
                    <div class="btn-group">
                    <form action="{{route('admin.tipos-propiedad.caracteristicas.destroy', [$tipo_propiedad->id, $caracteristica->id])}}" class="form-eliminar" method="post">
                       @csrf
                       @method('DELETE') 
                       <button class="btn btn-danger btn-sm" title="Eliminar" type="submit">
                        <i class="fas fa-trash-alt"></i>
                       </button>
                    </form>
                    </div>
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
    $('#caracteristica-autocompletar').autocomplete({
      source: "/admin/caracteristicas/autocompletar",
      autoFocus: true,
      select: function(event, p) {
        $('#caracteristica-autocompletar').val(p.item.nombre);
        $('#caracteristica-id').val(p.item.id);
        return false;
      }
    }).autocomplete('instance')._renderItem = function(ul, item) {
      return $('<li>').append($('<div>').html(item.nombre)).appendTo(ul);
    }
    $('.form-eliminar').on('submit', function() {
      return confirm("¿Estás seguro que deseas eliminar ésta característica para éste tipo de propiedad?");
    });
    $('#sortable').sortable({
      placeholder: 'fila-ordenable-placeholder',
      handle: '.fila-ordenable-handle',
      helper: fixWidthHelper,
      update: function(event, ui) {
        $.get(ui.item.data('url'), {orden : ui.item.index()}, function(data) {})
      }
      }).disableSelection();;
      function fixWidthHelper(e, ui) {
          let cloned = ui.clone();
        
          ui.children().each(function() {
              $(this).width($(this).width());
          });
          return ui.clone().css({'background-color' : '#eee'});
      }
  });
</script>
@endsection