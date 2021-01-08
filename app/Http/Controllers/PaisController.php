<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Pais;

class PaisController extends Controller
{
    public function index(Request $request)
    {
        if (!$request->ajax()) return redirect('/');

        $buscar = $request->buscar;
        $criterio = $request->criterio;
        
        if ($buscar==''){
            $paises = Pais::orderBy('id', 'desc')->paginate(5);
        }
        else{
            $paises = Pais::where($criterio, 'like', '%'. $buscar . '%')->orderBy('id', 'desc')->paginate(5);
        }
        

        return [
            'pagination' => [
                'total'        => $paises->total(),
                'current_page' => $paises->currentPage(),
                'per_page'     => $paises->perPage(),
                'last_page'    => $paises->lastPage(),
                'from'         => $paises->firstItem(),
                'to'           => $paises->lastItem(),
            ],
            'paises' => $paises
        ];
    }

    public function selectPais(Request $request)
    {
        if (!$request->ajax()) return redirect('/');
        
        $paises = Pais::select('id','nombre')
            ->orderBy('nombre', 'asc')->get();
        return [
            'paises' => $paises
        ];
    }

    public function store(Request $request)
    {
        if (!$request->ajax()) return redirect('/');
        $paises = new Pais();
        $paises->nombre = $request->nombre;
        $paises->save();
    }

    public function update(Request $request)
    {
        if (!$request->ajax()) return redirect('/');
        $paises = Pais::findOrFail($request->id);
        $paises->nombre = $request->nombre;
        $paises->save();
    }

    public function destroy(Request $request)
    {
        $paises= Pais::findOrfail($request->id);
        $paises->delete();
    }
}
