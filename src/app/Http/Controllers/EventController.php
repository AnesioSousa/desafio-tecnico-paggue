<?php

namespace App\Http\Controllers;

use App\Models\Event;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class EventController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:api');
        $this->middleware('role:producer|admin');
        // Automatically apply policy methods:
        //  index→viewAny, show→view, store→create, update→update, destroy→delete
        $this->authorizeResource(Event::class, 'event');
    }

    /** GET /events */
    public function index()
    {
        return Event::all();
    }

    /** POST /events */
    public function store(Request $request)
    {
        // Calls EventPolicy::create under the hood
        $data = $request->validate([
            'title' => 'required|string',
            'description' => 'nullable|string',
            'banner' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'date' => 'required|date',
            'start_time' => 'required|date_format:H:i:s',
            'end_time' => 'required|date_format:H:i:s',
            'city' => 'required|string',
            'venue' => 'required|string',
        ]);

        if ($request->hasFile('banner')) {
            $path = $request->file('banner')->store('events/banners', 's3');
            $data['banner_url'] = Storage::disk('s3')->url($path);
        }

        $data['producer_id'] = $request->user()->id;

        $event = Event::create($data);

        return response()->json($event, 201);
    }

    /** GET /events/{event} */
    public function show(Event $event)
    {
        return response()->json($event);
    }

    /** PUT/PATCH /events/{event} */
    public function update(Request $request, Event $event)
    {
        // Calls EventPolicy::update under the hood
        $data = $request->validate([
            'title' => 'sometimes|string',
            'description' => 'nullable|string',
            'banner' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'date' => 'sometimes|date',
            'start_time' => 'sometimes|date_format:H:i:s',
            'end_time' => 'sometimes|date_format:H:i:s',
            'city' => 'sometimes|string',
            'venue' => 'sometimes|string',
        ]);

        if ($request->hasFile('banner')) {
            $path = $request->file('banner')->store('events/banners', 's3');
            $data['banner_url'] = Storage::disk('s3')->url($path);
        }

        $event->update($data);

        return response()->json($event);
    }

    /** DELETE /events/{event} */
    public function destroy(Event $event)
    {
        // Calls EventPolicy::delete under the hood
        $event->delete();

        return response()->noContent();
    }
}
