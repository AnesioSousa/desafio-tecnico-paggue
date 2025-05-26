<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreSetorRequest;
use App\Http\Requests\UpdateSetorRequest;
use App\Models\Setor;

class SetorController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return Setor::all();
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
    public function store(StoreSetorRequest $request)
    {
        $data = $request->validate([
            'descricao' => 'required|string',
            'imagem' => 'nullable|string',
            'qtd_assentos' => 'required|integer',
            'tem_cobertura' => 'required|boolean'
        ]);
        return Setor::create($data);
    }

    /**
     * Display the specified resource.
     */
    public function show(Setor $setor)
    {
        return $setor;
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Setor $setor)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateSetorRequest $request, Setor $setor)
    {
        $data = $request->validate([
            'descricao' => 'sometimes|string',
            'imagem' => 'nullable|string',
            'qtd_assentos' => 'sometimes|integer',
            'tem_cobertura' => 'sometimes|boolean'
        ]);
        $setor->update($data);
        return $setor;
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Setor $setor)
    {
        $setor->delete();
        return response()->noContent();
    }
}
