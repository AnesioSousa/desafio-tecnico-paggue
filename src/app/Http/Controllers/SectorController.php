<?php

namespace App\Http\Controllers;

use App\Models\Sector;
use Illuminate\Http\Request;

class SectorController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:api');
    }

    public function index()
    {
        return Sector::all();
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'event_id' => 'required|exists:events,id',
            'name' => 'required|string|max:255',
        ]);
        return Sector::create($data);
    }

    public function show(Sector $sector)
    {
        return $sector;
    }

    public function update(Request $request, Sector $sector)
    {
        $sector->update($request->only(['event_id', 'name']));
        return $sector;
    }

    public function destroy(Sector $sector)
    {
        $sector->delete();
        return response()->noContent();
    }
}
