<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreIngressoRequest;
use App\Http\Requests\UpdateIngressoRequest;
use App\Models\Ingresso;

class IngressoController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return Ingresso::all();
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
    public function store(StoreIngressoRequest $request)
    {
        $data = $request->validate([
            'id_evento' => 'required|exists:eventos,id',
            'id_lote' => 'required|exists:lotes,id',
            'serial' => 'required|unique:ingressos,serial',
            'data_venda' => 'required|date'
        ]);
        return Ingresso::create($data);
    }

    /**
     * Display the specified resource.
     */
    public function show(Ingresso $ingresso)
    {
        return $ingresso;
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Ingresso $ingresso)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateIngressoRequest $request, Ingresso $ingresso)
    {
        $data = $request->validate([
            'serial' => 'sometimes|unique:ingressos,serial,' . $ingresso->id,
            'data_venda' => 'sometimes|date'
        ]);
        $ingresso->update($data);
        return $ingresso;
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Ingresso $ingresso)
    {
        $ingresso->delete();
        return response()->noContent();
    }
}
