<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Autor;
use App\Pais;

class AutorController extends Controller
{
    public function index(Request $request)
    {
        // if (!$request->ajax()) return redirect('/');

        // $buscar = $request->buscar;
        // $criterio = $request->criterio;
        
        // if ($buscar==''){
            $autores = Autor::join('paises','autores.id_pais','=','paises.id')
            ->select('autores.id','autores.nombre','autores.apellidos',
            'autores.email','autores.condicion','paises.nombre as nombrePais')
            ->paginate(9999999999999999999999999);
        // }
        // else{
            // $autores = Autor::join('paises','autores.id_pais','=','paises.id')
            // ->select('autores.id','autores.nombre','autores.apellidos',
            // 'autores.email','autores.condicion','paises.nombre as nombrePais')
            // ->where('autores.'.$criterio, 'like', '%'. $buscar . '%')->orderBy('autores.id', 'desc')->paginate(5);
        // }
        
        return [
            // 'pagination' => [
            //     'total'        => $autores->total(),
            //     'current_page' => $autores->currentPage(),
            //     'per_page'     => $autores->perPage(),
            //     'last_page'    => $autores->lastPage(),
            //     'from'         => $autores->firstItem(),
            //     'to'           => $autores->lastItem(),
            // ],
            'autores' => $autores
        ];
    }

    public function store(Request $request)
    {
        // if (!$request->ajax()) return redirect('/');
        $autores = new Autor();
        $autores->nombre = $request->nombre;
        $autores->apellidos = $request->apellidos;
        $autores->email = $request->email;
        $autores->id_pais = $request->id_pais;
        $autores->condicion = '1';
        $autores->save();
    }

    public function update(Request $request)
    {
        // if (!$request->ajax()) return redirect('/');
        $autores = Autor::findOrFail($request->id);
        $autores->nombre = $request->nombre;
        $autores->apellidos = $request->apellidos;
        $autores->email = $request->email;
        $autores->id_pais = $request->id_pais;
        $autores->condicion = '1';
        $autores->save();
    }

    public function desactivar(Request $request)
    {
        // if (!$request->ajax()) return redirect('/');
        $autores = Autor::findOrFail($request->id);
        $autores->condicion = '0';
        $autores->save();
    }

    public function activar(Request $request)
    {
        // if (!$request->ajax()) return redirect('/');
        $autores = Autor::findOrFail($request->id);
        $autores->condicion = '1';
        $autores->save();
    }

    public function destroy(Request $request)
    {
        $autores= Autor::findOrfail($request->id);
        $autores->delete();
    }
}