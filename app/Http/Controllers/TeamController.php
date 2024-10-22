<?php

namespace App\Http\Controllers;

use App\Models\Team;
use Illuminate\Http\Request;

class TeamController extends Controller
{
    public function index()
    {
        return response()->json(Team::all(), 200);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:teams',
        ]);

        $team = Team::create($validated);
        return response()->json($team, 201);
    }

    public function show(Team $team)
    {
        return response()->json($team, 200);
    }

    public function update(Request $request, Team $team)
    {
        $validated = $request->validate([
            'name' => 'string|max:255|unique:teams,name,' . $team->id,
        ]);

        $team->update($validated);
        return response()->json($team, 200);
    }

    public function destroy(Team $team)
    {
        $team->delete();
        return response()->json(null, 204);
    }
}
