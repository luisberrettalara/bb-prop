@extends('layouts.admin')
@section('content')
<div class="py-4">
  <div class="row">
    <div class="col-12">
      <div class="card">
        <div class="card-body">
          <div class="row">
            <div class="col-12">
              <h3>Estado de cuentas y facturación</h3>
            </div>
          </div>
          <div class="row">
            <div class="col-12">
              <form action="/admin/pedidos" method="GET">
                <div class="row">
                  <div class="col-md-2 col-12">
                    <div class="form-group">
                      <label><strong>Fecha desde</strong></label>
                      <input type="text" name="fechaDesde" id="fechaDesde" class="form-control" @if(array_key_exists('fechaDesde', $filtros)) value="{{$filtros['fechaDesde']}}" @endif>
                      <label><strong>Fecha hasta</strong></label>
                      <input type="text" name="fechaHasta" id="fechaHasta" class="form-control" @if(array_key_exists('fechaHasta', $filtros)) value="{{$filtros['fechaHasta']}}" @endif>
                    </div>
                  </div>
                  <div class="col-md-3 col-12">
                    <div class="form-group">
                      <label for="estado_id" class="form-label"><strong>Estado</strong></label>
                      <select id="estado_id" class="form-control" name="estado_id" value="{{ old('estado_id') }}">
                        <option value="">Todos</option>
                          @foreach ($estados as $estado)
                            <option value="{{$estado->id}}"
                              @if(array_key_exists('estado_id', $filtros)) 
                                {{ $filtros['estado_id'] == $estado->id? 'selected':'' }}
                              @endif>{{$estado->nombre}}
                            </option>
                          @endforeach
                        </select>
                    </div>
                  </div>
                  <div class="col-md-2 col-12">
                    <div class="form-group">
                      <label for="usuario" class="form-label"><strong>Usuario</strong></label> 
                      <input type="text" id="usuarios-autocompletar" name="usuario-razonSocial" class="form-control" @if(array_key_exists('usuario_id', $filtros)) value="{{$usuario}}" @endif autofocus> 
                      <input type="hidden" name="usuario_id" id="usuarios-id" @if(array_key_exists('usuario_id', $filtros)) value="{{$filtros['usuario_id']}}" @endif>
                    </div>
                  </div>
                  <div class="col-md-3 col-12">
                    <div class="form-group">
                      <label><strong>Resultados</strong></label>
                      <select type="text" class="form-control" name="items" value="{{ old('items') }}">
                        <option value="" disabled selected>Seleccione</option>
                        <option value="10" {{ $items == '10'? 'selected':'' }}>10</option>
                        <option value="20" {{ $items == '20'? 'selected':'' }}>20</option>
                        <option value="50" {{ $items == '50'? 'selected':'' }}>50</option>
                        <option value="100" {{ $items == '100'? 'selected':'' }}>100</option>
                      </select>
                    </div>
                  </div>
                  <div class="col-md-2 col-12 pt-2 mt-4">
                    <button type="submit" class="btn btn-primary">Buscar</button>
                  </div>
                </div>
              </form>
            </div>
          </div>
          <br/>
          <div class="table-container table-responsive">
            <table class="table table-striped">
              <thead>
                <th>#</th>
                <th>Fecha</th>
                <th>Paquete</th>
                <th>Usuario</th>
                <th>Estado</th>
                <th>Monto</th>
                <th>Factura</th>
              </thead>
              <tbody>
                @if($pedidos->count())
                  @foreach($pedidos as $pedido)
                    <tr>
                      <td>{{$pedido->id}}</td>
                      <td>{{$pedido->created_at->format('d/m/Y') }}</td>
                      <td>{{$pedido->paquete}}</td>
                      <td>{{$pedido->usuario}}</td>
                      <td>{{$pedido->estado}}</td>
                      <td>$ {{$pedido->total}}</td>
                      <td>
                        @if ($pedido->estaFacturado())
                        {{ $pedido->getNumeroFacturaFormato() }}
                        @endif
                      </td>
                    </tr>
                  @endforeach
                @else
                  <tr>
                    <td colspan="7">No hay registro !!</td>
                  </tr>
                @endif
              </tbody>
              <tfoot>
                <tr class="table-primary">
                  <td colspan="5"><strong>Total</strong></td>
                  <td><strong>$ {{$pedidos->sum('total')}}</strong></td>
                  <td></td>
                </tr>
              </tfoot>
            </table>
          </div>
        </div>
      {{ $pedidos->links('vendor.pagination.default') }} 
      </div>
    </div>
  </div>
</div>
@endsection
@section('post-scripts')
  <script type="text/javascript">
    $(function() {
      $('#fechaDesde').datepicker({
        dateFormat: "yy-mm-dd",
      });
      $('#fechaHasta').datepicker({
        dateFormat: "yy-mm-dd",
      });
      $('#usuarios-autocompletar').autocomplete({
        source: "/admin/pedidos/usuarios/autocompletar",
        autoFocus: true,
        select: function(event, p) {
          $('#usuarios-autocompletar').val(p.item.razon_social);
          $('#usuarios-id').val(p.item.id);
          return false;
        }
      }).autocomplete('instance')._renderItem = function(ul, item) {
        return $('<li>').append($('<div>').html(item.razon_social)).appendTo(ul);
      }
    });
  </script>
@endsection