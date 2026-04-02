<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Producto;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\Rule;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Producto::query();

        if ($request->has('search') && $request->search) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('sku', 'like', "%{$search}%")
                    ->orWhere('descripcion', 'like', "%{$search}%")
                    ->orWhere('clave_producto', 'like', "%{$search}%");
            });
        }

        // Pagination
        $perPage = $request->input('per_page', 15);
        $products = $query->simplePaginate($perPage);
        return response()->json($products);
    }

     /**
     * Display a listing of the resource.
     */
    public function showBySku(Request $request, String $sku)
    {
        $query = Producto::where(function ($q) use ($sku) {
                $q->where('sku', '=', $sku)
                    ->orWhere('clave_producto', '=', $sku);
            });
        return response()->json([
            "data"=> $query->first(),
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'SKU' => ['required', 'string', 'max:255'],
            'descripcion' => ['required', 'string'],
            'clave_producto' => ['required', 'string'],
            'UM' => ['required', 'string'],
            'activo' => ['boolean'],
        ]);

        $product = Producto::updateOrCreate(
            ['clave_producto' => $validatedData['clave_producto']],
            $validatedData
        );

        return response()->json([
            'message' => 'Product created successfully',
            'product' => $product
        ], 201);
    }
}
