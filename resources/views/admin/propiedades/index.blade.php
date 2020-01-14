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
                <h3>Listado de Propiedades</h3>
            </div>
            <div class="col-md-6 col-12 text-md-right">
                <a href="{{ route('admin.propiedades.create') }}" class="btn btn-verde">Añadir nueva Propiedad</a>
            </div>
          </div>
          <br/>
          <div class="table-container table-responsive">
            <table class="table table-striped">
             <thead>
               <th>#</th>
               <th>Título de la Propiedad</th>
               <th>Usuario</th>
               <th>Tipo de Inmueble</th>
               <th>Operación</th>
               <th>Estado</th>
               <th width="150px">Acciones</th>
             </thead>
              <tbody>
                @if($propiedades->count())
                  @foreach($propiedades as $prop)
                  <tr>
                    <td>{{$prop->id}}</td>
                    <td>{{$prop->titulo}}</td>
                    <td>{{$prop->usuario}}</td>
                    <td>{{$prop->tipoPropiedad}}</td>
                    <td>{{$prop->operacion}}</td>
                    <td>{{$prop->estado}}</td>
                    <td>
                      <div class="btn-group">
                        <a class="btn btn-primary btn-sm" title="Ver" href="{{route('propiedades.show', $prop->slug)}}">
                          <i class="fas fa-eye"></i>
                        </a>
                        <a class="btn btn-outline-secondary btn-sm" title="Editar" href="{{route('admin.propiedades.edit', $prop->id)}}">
                          <i class="fas fa-pencil-alt"></i>
                        </a>
                        <a class="btn btn-secondary btn-sm" title="Estados" href="{{route('admin.propiedades.estados', $prop->id)}}">
                          <i class="fas fa-list-ul"></i>
                        </a>
                      </div>
                    </td>
                  </tr>
                  @endforeach 
                @else
                 <tr>
                  <td colspan="7">No hay registro !!</td>
                </tr>
                @endif
              </tbody>
            </table>
          </div>
        </div>
      </div>
    </div>
  </div>

    {{ $propiedades->links() }}
</div>
@endsection