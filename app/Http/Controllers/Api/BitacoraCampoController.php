<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\BitacoraCampo;
use Illuminate\Http\Request;

class BitacoraCampoController extends Controller
{
    public function index()
    {
        $campos = BitacoraCampo::selectValores();
        return response()->json($campos);
    }
}
