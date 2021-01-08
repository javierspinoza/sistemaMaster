<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Sector;
use App\Oficina;

class SectorController extends Controller
{
    public function index(Request $request)
    {
        // if (!$request->ajax()) return redirect('/');

        $buscar = $request->buscar;
        $criterio = $request->criterio;
        
        if ($buscar==''){
            $sectores = Sector::join('oficinas','sectores.id_oficina','=','oficinas.id')
            ->select('sectores.id','sectores.nombre','sectores.id_oficina','oficinas.nombre as nomOficina')
            ->paginate(7);
        }
        else{
            $sectores = Sector::join('oficinas','sectores.id_oficina','=','oficinas.id')
            ->select('sectores.id','sectores.nombre','sectores.nombre','sectores.id_oficina','oficinas.nombre as nomOficina')
            ->where('sectores.'.$criterio, 'like', '%'. $buscar . '%')->orderBy('sectores.id', 'desc')->paginate(7);
        }
        
        return [
            'pagination' => [
                'total'        => $sectores->total(),
                'current_page' => $sectores->currentPage(),
                'per_page'     => $sectores->perPage(),
                'last_page'    => $sectores->lastPage(),
                'from'         => $sectores->firstItem(),
                'to'           => $sectores->lastItem(),
            ],
            'sectores' => $sectores
        ];
    }

    public function store(Request $request)
    {
        // if (!$request->ajax()) return redirect('/');
        $sectores = new Sector();
        $sectores->nombre = $request->nombre;
        $sectores->id_oficina = $request->id_oficina;
        $sectores->edo = '1';
        $sectores->save();
    }

    public function update(Request $request)
    {
        // if (!$request->ajax()) return redirect('/');
        $sectores = Sector::findOrFail($request->id);
        $sectores->nombre = $request->nombre;
        $sectores->id_oficina = $request->id_oficina;
        $sectores->edo = '1';
        $sectores->save();
    }

    public function desactivar(Request $request)
    {
        // if (!$request->ajax()) return redirect('/');
        $sectores = Sector::findOrFail($request->id);
        $sectores->edo = '0';
        $sectores->save();
    }

    public function activar(Request $request)
    {
        // if (!$request->ajax()) return redirect('/');
        $sectores = Sector::findOrFail($request->id);
        $sectores->edo = '1';
        $sectores->save();
    }

    public function destroy(Request $request)
    {
        $sectores= Sector::findOrfail($request->id);
        $sectores->delete();
    }
}
