<?php

namespace App\Http\Controllers;

use App\Exports\ProductosExport;
use App\Imports\ProductosImport;
use App\Models\Incidencia;
use App\Models\Producto;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Maatwebsite\Excel\Facades\Excel;

class ProductoController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //

        $productos = Producto::select(
        'productos.id as producto_id',
        'productos.SKU as producto_SKU',
        'productos.descripcion as producto_descripcion',
        'productos.DUN 14 as producto_dun14',
        'productos.EAN as producto_ean',
        'productos.activo as producto_activo', 
        'productos.created_at as producto_creacion')
        ->paginate(5);

        return Inertia::render('Productos/Productos.Index',[
            'productos' => $productos
        ]);
    }

    public function donwloadExportExample () 
    {
        return Excel::download(new ProductosExport, 'productos_example.xlsx');
    }

    public function viajesByProducto (Request $request)
    {
        $producto = $request['producto'];

      return  Incidencia::select('confirmacion_dts.*')
        ->join('ocs','incidencias.ocs_id','ocs.id')
        ->join('confirmacion_dts','ocs.confirmacion_dt_id','confirmacion_dts.id')
        ->where('incidencias.ean_id','=',$producto['producto_id'])
        ->groupBy('ocs.confirmacion_dt_id')
        ->get();
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
        $request->validate([
            'document' => ['required'],
        ]);


        try 
        {
            Excel::import(new ProductosImport, $request['document']);
            return redirect()->back();
        } 
        catch (\Throwable $th)
        {
            //throw $th;
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Producto $producto)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Producto $producto)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Producto $producto)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Producto $producto)
    {
        //
    }

    public function apiIndex(Request $request)
    {
       return Producto::select('productos.*')
       ->where('SKU','LIKE',$request['busqueda'])
       ->where('activo','=',1)
       ->get();
    } 
}
