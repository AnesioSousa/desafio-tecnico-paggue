<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\UpdateEventoRequest;
use App\Models\Event;
use App\Models\Produtor;

class EventController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:api');
        $this->authorizeResource(Event::class, 'event');
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return Event::all();
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
    public function store(Request $request)
    {
        $data = $request->validate([
            'title' => 'required|string',
            'description' => 'nullable|string',
            'banner_url' => 'nullable|url',
            'date' => 'required|date',
            'start_time' => 'required',
            'end_time' => 'required',
            'city' => 'required|string',
            'venue' => 'required|string',
        ]);
        $data['producer_id'] = $request->user()->id;
        return Event::create($data);
    }

    /**
     * Display the specified resource.
     */
    public function show(Produtor $produtor, Event $event)
    {
        return $event;
    }

    /*
      Display the specified resource.
     
    public function show(Produtor $produtor, Event $evento)
    {
        $evento = Event::where('id', $evento)
             ->where('produtor_id', $produtor)
             ->firstOrFail();
         return response()->json($evento);
    }
    */

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Event $evento)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Event $event)
    {
        $event->update($request->only(['title', 'description', 'banner_url', 'date', 'start_time', 'end_time', 'city', 'venue']));
        return $event;
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Event $event)
    {
        $event->delete();
        return response()->noContent();
    }

    /*
    public function showFromProdutor(Produtor $produtor, Event $evento)
    {
        if ($evento->produtor_id !== $produtor->id) {
            return response()->json(['message' => 'Event não pertence a este produtor.'], 404);
        }

        return response()->json($evento);
    }*/
}
