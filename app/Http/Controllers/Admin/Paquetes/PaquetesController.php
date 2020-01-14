<?php

namespace Inmuebles\Http\Controllers\Admin\Paquetes;

use Inmuebles\Models\Paquetes\Paquete;
use Inmuebles\Models\Paquetes\DetallePaquete;
use Inmuebles\Models\Paquetes\TipoCredito;
use Inmuebles\Models\Usuarios\User;

use Inmuebles\Services\Paquetes\PaquetesService;

use Illuminate\Http\Request;
use Inmuebles\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;

class PaquetesController extends Controller
{

  private $reglas = [
    'nombre' => 'required',
    'precio' => 'required|numeric|min:0',
    'creditos.*' => 'nullable|integer'
  ];

  /**
   * Display a listing of the resource.
   *
   * @return \Illuminate\Http\Response
   */
  public function index() {
    $paquetes = Paquete::orderBy('updated_at', 'desc')->paginate(10);

    return view('admin.paquetes.index')->with('paquetes', $paquetes);
  }

  /**
   * Show the form for creating a new resource.
   *
   * @return \Illuminate\Http\Response
   */
  public function create() {
    return view('admin.paquetes.create')
              ->with('creditos', $this->obtenerTiposCredito());
  }

  /**
   * Store a newly created resource in storage.
   *
   * @param  \Illuminate\Http\Request  $request
   * @return \Illuminate\Http\Response
   */
  public function store(Request $request) {
    $request->validate($this->reglas);
    // iniciamos una transaccion
    DB::transaction(function () use ($request) {

      // obtenemos las cantidades de los creditos seleccionados
      $creditos = array_filter($request->get('creditos'));

      // creamos un nuevo paquete

      $paquete = new Paquete($request->all());

      if ($request->has('por_defecto')) {
          // Buscamos si hay algun paquete que ya este marcado por defecto y lo ponemos en 0
          $paquetePorDefecto = Paquete::where('por_defecto', 1)->first();
          if ($paquetePorDefecto != null) {
            $paquetePorDefecto->por_defecto = 0;
            $paquetePorDefecto->save();
          }
          $paquete->por_defecto = 1;
      }
      else {
          $paquete->por_defecto = 0;
      }
      $paquete->save();
      // iteramos sobre las cantidades y creamos el detalle
      foreach ($creditos as $idTipoCredito => $cantidad) {
        $tipoCredito = TipoCredito::findOrFail($idTipoCredito);

        // ...y lo agregamos al paquete
        $paquete->agregarDetalle($tipoCredito, $cantidad);
      }
    });

    return redirect()->route('admin.paquetes.index')->with('exito', 'Paquete agregado con éxito');
  }

  /**
   * Display the specified resource.
   *
   * @param  \Inmuebles\Models\Paquetes\Paquete  $paquete
   * @return \Illuminate\Http\Response
   */
  public function show(Paquete $paquete) {
    return view('admin.paquetes.show')
              ->with('paquete', $paquete);
  }

  /**
   * Show the form for editing the specified resource.
   *
   * @param  \Inmuebles\Models\Paquetes\Paquete  $paquete
   * @return \Illuminate\Http\Response
   */
  public function edit(Paquete $paquete) {

    return view('admin.paquetes.edit')
              ->with('paquete', $paquete)
              ->with('creditos', $this->obtenerTiposCredito());
  }

  /**
   * Update the specified resource in storage.
   *
   * @param  \Illuminate\Http\Request  $request
   * @param  \Inmuebles\Models\Paquetes\Paquete  $paquete
   * @return \Illuminate\Http\Response
   */
  public function update(Request $request, Paquete $paquete) {
    $request->validate($this->reglas);

    // iniciamos una transaccion
    DB::transaction(function () use ($request, $paquete) {

      // obtenemos las cantidades de los creditos seleccionados
      $creditos = array_filter($request->get('creditos'));

     
      // completamos los datos fillables
      $paquete->fill($request->all());

      if ($request->has('por_defecto')) {
         // Buscamos si hay algun paquete que ya este marcado por defecto y lo ponemos en 0
        $paquetePorDefecto = Paquete::where('por_defecto', 1)->first();
        if($paquetePorDefecto!=null) {
          $paquetePorDefecto->por_defecto = 0;
          $paquetePorDefecto->save();
        }
        $paquete->por_defecto = 1;
      }
      else {
          $paquete->por_defecto = 0;
      }
      $paquete->save();

      // recorremos los créditos que vienen del request
      // pueden ser nuevos o actualizar cantidades
      foreach ($creditos as $idTipoCredito => $cantidad) {
        $tipoCredito = TipoCredito::findOrFail($idTipoCredito);

        // chequeamos si ya existe
        if ($paquete->tieneCreditosDeTipo($tipoCredito)) {
          // existe, entonces lo obtenemos
          $detalle = $paquete->obtenerDetalleParatipo($tipoCredito);

          // si la cantidad difiere la actualizamos
          if ($detalle->cantidad != $cantidad) {
            $detalle->actualizarCantidad($cantidad);
          }
        }
        else {
          // en cambio si no existe lo agregamos
          $paquete->agregarDetalle($tipoCredito, $cantidad);
        }
      }

      // eliminamos los huérfanos (que no vienen por el request)
      foreach ($paquete->detalle as $detalle) {
        if (!isset($creditos[$detalle->tipoCredito->id])) {
          $detalle->delete();
        }
      }
    });

    return redirect()->route('admin.paquetes.index')->with('exito', 'Se han guardado los cambios en el Paquete');
  }

  /**
   * Remove the specified resource from storage.
   *
   * @param  \Inmuebles\Models\Paquetes\Paquete  $paquete
   * @return \Illuminate\Http\Response
   */
  public function destroy(Paquete $paquete) {
      $paquete->delete();

      return redirect()->route('admin.paquetes.index')->with('exito', 'El Paquete fue eliminado con éxito');
  }

  public function disponibilizar(Paquete $paquete) {
    $paquete->cambiarDisponibilidad();

    return redirect()->route('admin.paquetes.index')->with('exito', 'Se ha cambiado la disponibilidad del Paquete');
  }

  public function seleccionar(User $user) {
    $paquetes = Paquete::orderBy('updated_at', 'desc')->get();

    return view('admin.paquetes.seleccionar')->with('paquetes', $paquetes)
                                             ->with('usuario', $user);
  }

  public function asignar(User $user, Request $request, PaquetesService $service) {
    $request->validate([
      'paquetes' => 'required',
      'paquetes.*' => 'exists:paquetes,id'
    ]);

    $paquetes = Paquete::findMany($request->get('paquetes'));

    // delegamos a la capa de servicios la asignación
    $service->asignarVarios($user, $paquetes);

    return redirect()->route('admin.usuarios.creditos', [ $user ])
                     ->with('exito', 'Se han asignado los Créditos al Anunciante');;
  }

  private function obtenerTiposCredito() {
    return TipoCredito::orderBy('destacado', 'desc')
                      ->orderBy('duracion_en_dias', 'asc')
                      ->orderBy('dias_al_vencimiento', 'asc')
                      ->get();
  }
}
