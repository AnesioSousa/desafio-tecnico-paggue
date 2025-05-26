<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreCupomRequest;
use App\Http\Requests\UpdateCupomRequest;
use App\Models\Cupom;

class CupomController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        Cupom::all();
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreCupomRequest $request)
    {
        $data = $request->validate([
            'id_ingresso' => 'required|exists:ingressos,id',
            'serial' => 'required|unique:cupons,serial',
            'desconto' => 'required|numeric|min:0|max:100',
            'data_consumo' => 'nullable|date'
        ]);
        return Cupom::create($data);
    }

    /**
     * Display the specified resource.
     */
    public function show(Cupom $cupom)
    {
        return $cupom;
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Cupom $cupom)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateCupomRequest $request, Cupom $cupom)
    {
        $data = $request->validate([
            'serial' => 'sometimes|unique:cupons,serial,' . $cupom->id,
            'desconto' => 'sometimes|numeric|min:0|max:100',
            'data_consumo' => 'nullable|date'
        ]);
        $cupom->update($data);
        return $cupom;
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Cupom $cupom)
    {
        $cupom->delete();
        return response()->noContent();
    }
}
