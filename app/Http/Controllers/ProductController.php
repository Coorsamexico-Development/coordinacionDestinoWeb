<?php

namespace App\Http\Controllers;

use App\Models\Producto;
use Illuminate\Http\Request;
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
                $q->where('SKU', 'like', "%{$search}%")
                    ->orWhere('descripcion', 'like', "%{$search}%")
                    ->orWhere('EAN', 'like', "%{$search}%");
            });
        }

        // Pagination
        $perPage = $request->input('per_page', 15);
        $products = $query->simplePaginate($perPage);

        return response()->json($products);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'SKU' => ['required', 'string', 'max:255', 'unique:productos,SKU'],
            'descripcion' => ['required', 'string'],
            'DUN 14' => ['nullable', 'string'],
            'EAN' => ['nullable', 'string'],
            'activo' => ['boolean'],
            'cantidadPOD' => ['nullable', 'integer'],
        ]);

        $product = Producto::create($validatedData);

        return response()->json([
            'message' => 'Product created successfully',
            'product' => $product
        ], 201);
    }
}
