<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreLoteRequest;
use App\Http\Requests\UpdateLoteRequest;
use App\Models\Lote;

class LoteController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return Lote::all();
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
    public function store(StoreLoteRequest $request)
    {
        $data = $request->validate([
            'data_inicio' => 'required|date',
            'data_vencimento' => 'required|date|after_or_equal:data_inicio'
        ]);
        return Lote::create($data);
    }

    /**
     * Display the specified resource.
     */
    public function show(Lote $lote)
    {
        return $lote;
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Lote $lote)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateLoteRequest $request, Lote $lote)
    {
        $data = $request->validate([
            'data_inicio' => 'sometimes|date',
            'data_vencimento' => 'sometimes|date|after_or_equal:data_inicio'
        ]);
        $lote->update($data);
        return $lote;
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Lote $lote)
    {
        $lote->delete();
        return response()->noContent();
    }
}
