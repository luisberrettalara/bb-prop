<?php

namespace Inmuebles\Http\Controllers;

use Illuminate\Http\Request;
use Inmuebles\Models\Comun\Barrio;

class BarriosController extends Controller
{
    public function listar($place_id) {
    	return Barrio::whereHas('localidad', function($query) use ($place_id) {
    		$query->where('google_place_id', $place_id);
    	})->orderBy('nombre')->get();
    }

    public function listarPorId($id) {
    	return Barrio::where('localidad_id', $id)->orderBy('nombre')->get();
    }

    public function autoCompletar(Request $request) {
      return Barrio::where('nombre', 'like', '%'.$request->query('term').'%')->whereHas('localidad', function($query) use ($request) {
        $query->where('google_place_id', $request->get('loc_place_id'));
      })->limit(10)->get();
    }
}
