<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Auth::routes();

Route::get('tos', function() { return view('tos'); });

Route::group(['middleware' => 'admin', 'namespace' => 'Admin', 'prefix' => 'admin', 'as' => 'admin.'], function () {

    Route::resource('operaciones', 'OperacionesController');
    Route::resource('usos', 'UsosController');
    Route::resource('tipologias', 'TipologiasController');

    Route::get('usuarios/autocompletar', 'UsuariosController@autoCompletar');
    Route::get('usuarios/{user}/creditos', 'UsuariosController@creditos')->name('usuarios.creditos');
    Route::resource('usuarios', 'UsuariosController');

    Route::get('caracteristicas/autocompletar', 'CaracteristicasController@autoCompletar');
    Route::resource('caracteristicas.opciones', 'CaracteristicasOpcionesController')->except(['create', 'show']);
    Route::resource('caracteristicas', 'CaracteristicasController');

    Route::get('tipos-propiedad/{tipoPropiedad}/caracteristicas/{caracteristica}/reordenar', 'TiposPropiedadCaracteristicasController@reordenar')->name('tipos-propiedad.caracteristicas.reordenar');
    Route::resource('tipos-propiedad.caracteristicas', 'TiposPropiedadCaracteristicasController')->except(['edit', 'update', 'create']);
    Route::resource('tipos-propiedad', 'TiposPropiedadController');

    Route::resource('barrios', 'BarriosController');
    Route::get('propiedad/{id}/archivo', [
        'as' => 'propiedades.descargar',
        'uses' => 'PropiedadesController@descargar'
    ]);

    Route::get('propiedades/{id}/edit', 'PropiedadesController@edit');
    Route::resource('propiedades', 'PropiedadesController');
    Route::get('propiedades/{propiedad}/estados', 'PropiedadesController@estados')->name('propiedades.estados');

    Route::resource('paquetes', 'Paquetes\PaquetesController');
    Route::get('paquetes/{paquete}/disponibilizar', 'Paquetes\PaquetesController@disponibilizar')->name('paquetes.disponibilizar');
    Route::get('paquetes/usuario/{user}', 'Paquetes\PaquetesController@seleccionar')->name('paquetes.usuario.seleccionar');
    Route::post('paquetes/usuario/{user}', 'Paquetes\PaquetesController@asignar')->name('paquetes.usuario.asignar');

    Route::resource('tipos-credito', 'Paquetes\TiposCreditoController');

    Route::get('/pedidos/usuarios/autocompletar', 'PedidosController@autoCompletar');
    Route::get('pedidos', 'PedidosController@index')->name('pedidos.index');

    Route::get('pedidos/facturar/{pedido}', function (Inmuebles\Models\Pedidos\Pedido $pedido) {
        Inmuebles\Jobs\FacturarPedido::dispatch($pedido)->onQueue('facturas');
    });
});

Route::get('/', 'HomeController@index')->name('home');

Route::group(['prefix' => 'propiedades'], function () {
    Route::get ('busqueda', 'BusquedaController@buscar')->name('propiedades.buscar');

    Route::post('{id}/compartir','PropiedadesController@compartir')->name('propiedades.compartir');
    Route::get ('{id}/interesado','PropiedadesController@estaInteresado')->name('propiedades.estaInteresado');
    Route::post('{id}/interesado','PropiedadesController@interesarse')->name('propiedades.interesarse');
    Route::post('{id}/contactar','PropiedadesController@contactar')->name('propiedades.contactar');

    Route::get ('{propiedad}/publicar', 'PropiedadesController@seleccionarCreditoParaPublicar');
    Route::post('{propiedad}/publicar', 'PropiedadesController@publicar')->name('propiedades.publicar');
    Route::get ('{propiedad}/pausar','PropiedadesController@pausar')->name('propiedades.pausar');
    Route::get ('{propiedad}/reanudar','PropiedadesController@reanudar')->name('propiedades.reanudar');
    Route::get ('{propiedad}/finalizar','PropiedadesController@finalizar')->name('propiedades.finalizar');
    Route::get ('{propiedad}/baja','PropiedadesController@darDeBaja')->name('propiedades.baja');
    Route::get ('{propiedad}/imprimir','PropiedadesController@imprimir')->name('propiedades.imprimir');
});

Route::group(['prefix' => 'pedidos'], function () {
    Route::get('{pedido}/exito', 'PedidosController@exito')->name('pedidos.exito');
    Route::get('{pedido}/pendiente', 'PedidosController@pendiente')->name('pedidos.pendiente');
    Route::get('{pedido}/rechazado', 'PedidosController@rechazado')->name('pedidos.rechazado');

    Route::post('ipn', 'PedidosController@ipn');
});

Route::group(['prefix' => 'paquetes'], function () {
    Route::get('comprar', 'PaquetesController@listar')->name('paquetes.comprar');
    Route::post('comprar', 'PaquetesController@comprar');
});

Route::get('propiedad/{id}/archivo', [
    'as' => 'propiedades.descargar',
    'uses' => 'PropiedadesController@descargar'
]);

Route::post('/contactar', 'ContactoController@contactar')->name('admin.contactar'); 

Route::get('propiedades/{propiedad}/usuarios/{usuario}/favoritas', 'UsuariosController@favorita')->name('propiedades.users.fav');

Route::get('propiedades/favoritas', 'PropiedadesController@propiedadesFavoritas')->name('propiedades.favoritas');

Route::get('propiedades/{id}/edit', 'PropiedadesController@edit');

Route::get('propiedades/{id}/{slug}', 'PropiedadesController@show');

Route::resource('propiedades', 'PropiedadesController');

Route::get('propiedades/{id}/fotos/{fid}/portada','FotosController@definirPortada');

Route::post('fotos/temporal','FotosController@subirTemporal');

Route::resource('propiedades.fotos', 'FotosController');

Route::get('provincias/{id}/localidades', 'LocalidadesController@listar');

Route::get('localidades/{place_id}/barrios', 'BarriosController@listar');

Route::get('localidad/{id}/barrios', 'BarriosController@listarPorId');

Route::get('barrios/autocompletar', 'BarriosController@autoCompletar');

Route::get('tipo-propiedad/{id}/caracteristicas', 'TiposPropiedadController@listar');

Route::get('/login/facebook', 'Auth\LoginController@redirectToProvider')->name('social.auth');
Route::get('/login/facebook/callback', 'Auth\LoginController@handleProviderCallback');

Route::get('usuarios/mi-perfil', 'UsuariosController@VerMiPerfil')->name('perfil');

Route::get('usuarios/editar', 'UsuariosController@editarMiPerfil')->name('perfil.editar');

Route::patch('usuarios/{usuario}/update', [
   'as' => 'usuarios.update', 'uses' => 'UsuariosController@update'
]);

Route::get('usuarios/completar-perfil', 'UsuariosController@completarDatos');

Route::patch('usuarios/{usuario}/completar-perfil', 'UsuariosController@updateARolAnunciante')->name('usuarios.completar-perfil');