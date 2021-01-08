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

Route::group(['middleware'=>['guest']],function(){
    Route::get('/','Auth\LoginController@showLoginForm');
    Route::post('/login', 'Auth\LoginController@login')->name('login');
    // Route::get('/login', 'Auth\LoginController@login');
});

Route::group(['middleware'=>['auth']],function(){
    
    Route::post('/logout', 'Auth\LoginController@logout')->name('logout');
    
    Route::get('/main', function () {
        return view('contenido/contenido');
    })->name('main');

    Route::group(['middleware' => ['Almacenero']], function () {
        Route::get('/categoria', 'CategoriaController@index');
        Route::post('/categoria/registrar', 'CategoriaController@store');
        Route::put('/categoria/actualizar', 'CategoriaController@update');
        Route::put('/categoria/desactivar', 'CategoriaController@desactivar');
        Route::put('/categoria/activar', 'CategoriaController@activar');
        Route::get('/categoria/selectCategoria', 'CategoriaController@selectCategoria');

        Route::get('/articulo', 'ArticuloController@index');
        Route::post('/articulo/registrar', 'ArticuloController@store');
        Route::put('/accesorio/actualizar', 'AccesorioController@update');
        Route::post('/accesorio/registrar', 'AccesorioController@store');
        Route::post('/accesorio/eliminar', 'AccesorioController@destroy');
        Route::put('/articulo/desactivar', 'ArticuloController@desactivar');
        Route::put('/articulo/activar', 'ArticuloController@activar');
        Route::get('/articulo/buscarArticulo', 'ArticuloController@buscarArticulo');
        Route::get('/articulo/listarArticulo', 'ArticuloController@listarArticulo');

        Route::get('/proveedor', 'ProveedorController@index');
        Route::post('/proveedor/registrar', 'ProveedorController@store');
        Route::put('/proveedor/actualizar', 'ProveedorController@update');
        Route::get('/proveedor/selectProveedor', 'ProveedorController@selectProveedor');

        Route::get('/ingreso', 'IngresoController@index');
        Route::post('/ingreso/registrar', 'IngresoController@store');
        Route::put('/ingreso/desactivar', 'IngresoController@desactivar');
        Route::get('/ingreso/obtenerCabecera', 'IngresoController@obtenerCabecera');
        Route::get('/ingreso/obtenerDetalles', 'IngresoController@obtenerDetalles');

    });

    Route::group(['middleware' => ['Vendedor']], function () {
        Route::get('/cliente', 'ClienteController@index');
        Route::post('/cliente/registrar', 'ClienteController@store');
        Route::post('/cliente/registrar2', 'ClienteController@store2');
        Route::put('/cliente/actualizar', 'ClienteController@update');        
        Route::put('/cliente/actualizar2', 'ClienteController@update2');        
        Route::get('/cliente/selectCliente', 'ClienteController@selectCliente');

        Route::get('/articulo/buscarArticuloVenta', 'ArticuloController@buscarArticuloVenta');
        Route::get('/articulo/listarArticuloVenta', 'ArticuloController@listarArticuloVenta'); 

        Route::get('/venta', 'VentaController@index');
        Route::post('/venta/registrar', 'ventaController@store');
        Route::put('/venta/desactivar', 'ventaController@desactivar');
        Route::get('/venta/obtenerCabecera', 'ventaController@obtenerCabecera');
        Route::get('/venta/obtenerDetalles', 'ventaController@obtenerDetalles');
    });

    Route::group(['middleware' => ['Administrador']], function () {
        
        Route::get('/accesorio', 'AccesorioController@index');

        Route::get('pais','PaisController@index');
        Route::get('/pais/selectPais','PaisController@selectPais');
        Route::post('pais/registrar','PaisController@store');
        Route::put('pais/actualizar','PaisController@update');
        Route::post('pais/eliminar','PaisController@destroy');

        Route::get('/autor', 'AutorController@index');
        Route::post('/autor/registrar', 'AutorController@store');
        Route::put('/autor/actualizar', 'AutorController@update');
        Route::post('/autor/eliminar','AutorController@destroy');
        Route::put('/autor/desactivar', 'AutorController@desactivar');
        Route::put('/autor/activar', 'AutorController@activar');

        Route::get('tp_concepto','Tp_conceptoController@index'); 
        Route::post('/tp_concepto/registrar','Tp_conceptoController@store'); 
        Route::put('/tp_concepto/actualizar','Tp_conceptoController@update'); 
        Route::post('/tp_concepto/eliminar','Tp_conceptoController@destroy');
        Route::get('/tp_concepto/selectTp_concepto','Tp_conceptoController@selectTp_concepto');

        Route::get('/libro', 'LibroController@index');
        Route::post('/libro/registrar', 'LibroController@store');
        Route::put('/libro/actualizar', 'LibroController@update');
        Route::post('/libro/eliminar','LibroController@destroy');

        Route::get('/sector', 'SectorController@index');
        Route::post('/sector/registrar', 'SectorController@store');
        Route::put('/sector/actualizar', 'SectorController@update');
        Route::post('/sector/eliminar','SectorController@destroy');
        Route::put('/sector/desactivar', 'SectorController@desactivar');
        Route::put('/sector/activar', 'SectorController@activar');
        Route::get('/oficina/selectOficina','OficinaController@selectOficina');

        Route::get('/categoria', 'CategoriaController@index');
        Route::post('/categoria/registrar', 'CategoriaController@store');
        Route::put('/categoria/actualizar', 'CategoriaController@update');
        Route::post('/categoria/eliminar','CategoriaController@destroy');
        Route::put('/categoria/desactivar', 'CategoriaController@desactivar');
        Route::put('/categoria/activar', 'CategoriaController@activar');

    });

});

//Route::get('/home', 'HomeController@index')->name('home');
