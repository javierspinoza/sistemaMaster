<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\TpConcepto;

class Tp_conceptoController extends Controller
{
   
        //mostrar datos de la tabla
        public function index(Request $request)
        {
            $buscar=$request->buscar;
            $criterio=$request->criterio;
    
            if ($buscar=='') {
                $tpconceptos= TpConcepto::orderBy('nombre','asc')->paginate(4);
            }else {
                $tpconceptos= TpConcepto::where($criterio, 'like', '%'.$buscar. '%')->orderby('nombre','asc')->paginate(4);
            }
    
            // GET para obtener
            // POST guardar en la bd
            // PUT actualizar o eliminar
                    
            return [
    
                'pagination'=>[
                    'total'=> $tpconceptos -> total(),
                    'current_page'=> $tpconceptos -> currentPage(),
                    'per_page'=> $tpconceptos -> perPage(),
                    'last_page'=> $tpconceptos -> lastPage(),
                    'from'=> $tpconceptos -> firstItem(),
                    'to'=> $tpconceptos -> lastItem(),
                ],
                'tpconceptos'=>$tpconceptos
            ];
        }
        public function selectTp_concepto(Request $request)
        {
            $tpconceptos = TpConcepto::select('id','nombre')
                ->orderBy('nombre', 'asc')->get();
            return [
                'tpconceptos' => $tpconceptos];
        }
        //guardar datos en la bd
        public function store(Request $request)
        {
            $tpconceptos= new TpConcepto();
            $tpconceptos->codigo = $request->codigo;
            $tpconceptos->nombre = $request->nombre;
          
            $tpconceptos->save();
        }
        
        //actualizar datos
        public function update(Request $request)
        {
            $tpconceptos= TpConcepto::findOrfail($request->id);
            $tpconceptos->codigo = $request->codigo;
            $tpconceptos->nombre = $request->nombre;
            $tpconceptos->save();
        }
    
        //eliminar datos
        public function destroy(Request $request)
        {
            $tpconceptos= TpConcepto::findOrfail($request->id);
            $tpconceptos->delete();
        }
}
