<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreEventoRequest;
use App\Http\Requests\UpdateEventoRequest;
use App\Models\Evento;
use App\Models\Produtor;

class EventoController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return Evento::all();
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
        // Banner pode ser só um link pra um bucket do aws?
    public function store(StoreEventoRequest $request)
    {
        $data = $request->validate([
                'id_produtor' => 'required|exists:produtores,id',
                'nome' => 'required|string|max:255',
                'data' => 'required|date',
                'cidade' => 'required|string',
                'local' => 'required|string',
                'hora_inicio' => 'required',
                'hora_fim' => 'required',
                'banner' => 'nullable|image'
            ]);

        if ($request->hasFile('banner')) {
            $path = $request->file('banner')->store('banners', env('APP_ENV') === 'production' ? 's3' : 'public');
            $data['banner'] = Storage::disk(env('APP_ENV') === 'production' ? 's3' : 'public')->url($path);
        }

        return Evento::create($data);
    }

    /**
     * Display the specified resource.
     */
    public function show(Produtor $produtor, Evento $evento)
    {
        return $evento;
    }

    /*
      Display the specified resource.
     
    public function show(Produtor $produtor, Evento $evento)
    {
        $evento = Evento::where('id', $evento)
             ->where('produtor_id', $produtor)
             ->firstOrFail();
         return response()->json($evento);
    }
    */

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Evento $evento)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateEventoRequest $request, Evento $evento)
    {
        $data = $request->validate([
            'nome' => 'required|string|max:255',
            'data' => 'sometimes|date',
            'cidade' => 'sometimes|string',
            'local' => 'sometimes|string',
            'hora_inicio' => 'sometimes',
            'hora_fim' => 'sometimes',
            'banner' => 'nullable|image'
        ]);

        if ($request->hasFile('banner')) {
            $path = $request->file('banner')->store('banners', env('APP_ENV') === 'production' ? 's3' : 'public');
            $data['banner'] = Storage::disk(env('APP_ENV') === 'production' ? 's3' : 'public')->url($path);
        }

        $evento->update($data);
        return $evento;
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Evento $evento)
    {
        $evento->delete();
        return response()->noContent();
    }

    public function showFromProdutor(Produtor $produtor, Evento $evento)
{
    if ($evento->produtor_id !== $produtor->id) {
        return response()->json(['message' => 'Evento não pertence a este produtor.'], 404);
    }

    return response()->json($evento);
}
}
