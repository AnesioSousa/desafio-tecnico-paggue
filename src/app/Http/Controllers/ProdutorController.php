<?php
// src/app/Http/Controllers/ProdutorController.php
namespace App\Http\Controllers;

use App\Http\Requests\StoreProdutorRequest;
use App\Http\Requests\UpdateProdutorRequest;
use App\Models\Produtor;

class ProdutorController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index() 
    {
        return Produtor::all();
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
    public function store(StoreProdutorRequest $request)
    {
        $data = $request->validate([
            'nome' => 'required|string',
            'email' => 'required|email|unique:produtores',
            'senha' => 'required|min:6'
        ]);
        $data['senha'] = bcrypt($data['senha']);
        return Produtor::create($data);
    }

    /**
     * Display the specified resource.
     */
    public function show(Produtor $produtor)
    {
        return response()->json($produtor);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Produtor $produtor)
    {
        //
    }
    

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateProdutorRequest $request, Produtor $produtor)
    {
        $data = $request->validate([
            'nome' => 'sometimes|string',
            'email' => 'sometimes|email|unique:produtores,email,' . $produtor->id,
            'senha' => 'sometimes|min:6'
        ]);
        if (isset($data['senha'])) $data['senha'] = bcrypt($data['senha']);
        $produtor->update($data);
        return $produtor;
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Produtor $produtor)
    {
        $produtor->forceDelete();
        return response()->noContent();
    }
    
}
